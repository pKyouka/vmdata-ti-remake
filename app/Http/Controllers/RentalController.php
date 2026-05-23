<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\User;
use App\Models\Vm;
use App\Models\VMRental;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RentalController extends Controller
{
    public function index()
    {
        // Only admins can view all rentals
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized: Only admins can access this page');
        }

        $rentals = Rental::with(['user', 'vm', 'admin'])->paginate(10);

        // Combine all VM rentals into one query, then filter in-memory to avoid N+1
        $allVmRentals = \App\Models\VMRental::with(['user', 'vm'])->latest()->get();
        $pendingVmrentals = $allVmRentals->where('status', 'pending');
        $vmrentals = $allVmRentals->whereNotIn('status', ['pending']);

        return view('rentals.index', compact('rentals', 'pendingVmrentals', 'vmrentals'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only admins can create rentals');
        }

        // Cache admin/user lists for 1 hour
        $users = cache()->remember('users_non_admin', 3600, fn() =>
            User::where('role', 'user')->get()
        );

        $vms = Vm::all();

        $admins = cache()->remember('users_admin', 3600, fn() =>
            User::where('role', 'admin')->get()
        );

        return view('rentals.create', compact('users', 'vms', 'admins'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only admins can create rentals');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'vm_id' => 'required|exists:vms,id',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'status' => 'required|in:pending,active,expired,cancelled',
            'admin_id' => 'required|exists:users,id',
            'vm_username' => 'nullable|string|max:255',
            'vm_password' => 'nullable|string|max:255',
            'vm_ip_address' => 'nullable|ipv4|ipv6',
        ]);

        $data = $request->all();

        // Parse DD/MM/YYYY to Y-m-d format for database
        try {
            $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->start_date);
            $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->end_date);

            $data['start_date'] = $startDate->format('Y-m-d');
            $data['end_date'] = $endDate->format('Y-m-d');
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Format tanggal tidak valid. Gunakan format DD/MM/YYYY'])->withInput();
        }

        // Validate that end_date is after or equal to start_date
        if ($data['end_date'] < $data['start_date']) {
            return back()->withErrors(['end_date' => 'Tanggal selesai harus lebih besar atau sama dengan tanggal mulai'])->withInput();
        }

        Rental::create($data);

        // Clear cache since new rental added
        cache()->forget('users_non_admin');
        cache()->forget('users_admin');

        return redirect()->route('rentals.index')->with('success', 'Rental berhasil ditambahkan.');
    }

    public function show(Rental $rental)
    {
        return view('rentals.show', compact('rental'));
    }

    public function edit(Rental $rental)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only admins can edit rentals');
        }

        $users = cache()->remember('users_non_admin', 3600, fn() =>
            User::where('role', 'user')->get()
        );

        $vms = Vm::all();

        $admins = cache()->remember('users_admin', 3600, fn() =>
            User::where('role', 'admin')->get()
        );

        // Format dates to DD/MM/YYYY for display
        $rental->start_date_formatted = $rental->start_date ? $rental->start_date->format('d/m/Y') : '';
        $rental->end_date_formatted = $rental->end_date ? $rental->end_date->format('d/m/Y') : '';

        return view('rentals.edit', compact('rental', 'users', 'vms', 'admins'));
    }

    public function update(Request $request, Rental $rental)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only admins can update rentals');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'vm_id' => 'required|exists:vms,id',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'status' => 'required|in:pending,active,expired,cancelled',
            'admin_id' => 'required|exists:users,id',
            'vm_username' => 'nullable|string|max:255',
            'vm_password' => 'nullable|string|max:255',
            'vm_ip_address' => 'nullable|ipv4|ipv6',
        ]);

        $data = $request->all();

        // Parse DD/MM/YYYY to Y-m-d format for database
        try {
            $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->start_date);
            $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->end_date);

            $data['start_date'] = $startDate->format('Y-m-d');
            $data['end_date'] = $endDate->format('Y-m-d');
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Format tanggal tidak valid. Gunakan format DD/MM/YYYY'])->withInput();
        }

        // Validate that end_date is after or equal to start_date
        if ($data['end_date'] < $data['start_date']) {
            return back()->withErrors(['end_date' => 'Tanggal selesai harus lebih besar atau sama dengan tanggal mulai'])->withInput();
        }

        $rental->update($data);

        // Clear cache
        cache()->forget('users_non_admin');
        cache()->forget('users_admin');

        return redirect()->route('rentals.index')->with('success', 'Rental berhasil diperbarui.');
    }

    public function destroy(Rental $rental)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only admins can delete rentals');
        }

        $rental->delete();

        cache()->forget('users_non_admin');
        cache()->forget('users_admin');

        return redirect()->route('rentals.index')->with('success', 'Rental berhasil dihapus.');
    }

    /**
     * Update rental status via AJAX (admin only)
     */
    public function updateStatus(Request $request, Rental $rental)
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['active', 'inactive', 'expired', 'pending', 'cancelled'])],
        ]);

        $old = $rental->status;
        $rental->status = $validated['status'];
        $rental->save();

        return response()->json([
            'success' => true,
            'old' => $old,
            'status' => $rental->status,
            'message' => 'Status updated to ' . $rental->status,
        ]);
    }
}
