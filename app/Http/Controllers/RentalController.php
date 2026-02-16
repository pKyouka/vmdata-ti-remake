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
        $rentals = Rental::with(['user', 'vm', 'admin'])->paginate(10);

        // If admin, also include pending VM rental requests so admin can respond
        $pendingVmrentals = [];
        $vmrentals = collect();
        if (auth()->check() && auth()->user()->role === 'admin') {
            $pendingVmrentals = \App\Models\VMRental::with(['user', 'vm'])->where('status', 'pending')->latest()->get();
            // Also fetch processed VM rentals (non-pending) so we can show users who rented VMs
            $vmrentals = \App\Models\VMRental::with(['user', 'vm'])->where('status', '!=', 'pending')->latest()->get();
        }

        return view('rentals.index', compact('rentals', 'pendingVmrentals', 'vmrentals'));
    }

    public function create()
    {
        // Get all users (except admins)
        $users = User::where('role', 'user')->get();

        // Get all VMs
        $vms = Vm::all();

        // Get all admins
        $admins = User::where('role', 'admin')->get();

        return view('rentals.create', compact('users', 'vms', 'admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'vm_id' => 'required',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'status' => 'required',
            'admin_id' => 'required',
            'vm_username' => 'nullable|string|max:255',
            'vm_password' => 'nullable|string|max:255',
            'vm_ip_address' => 'nullable|string|max:255',
        ]);

        $data = $request->all();

        // Parse DD/MM/YYYY to Y-m-d format for database
        try {
            $data['start_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
            $data['end_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Format tanggal tidak valid. Gunakan format DD/MM/YYYY'])->withInput();
        }

        // Validate that end_date is after or equal to start_date
        if ($data['end_date'] < $data['start_date']) {
            return back()->withErrors(['end_date' => 'Tanggal selesai harus lebih besar atau sama dengan tanggal mulai'])->withInput();
        }

        Rental::create($data);

        return redirect()->route('rentals.index')->with('success', 'Rental berhasil ditambahkan.');
    }

    public function show(Rental $rental)
    {
        return view('rentals.show', compact('rental'));
    }

    public function edit(Rental $rental)
    {
        // Get all users (except admins)
        $users = User::where('role', 'user')->get();

        // Get all VMs
        $vms = Vm::all();

        // Get all admins
        $admins = User::where('role', 'admin')->get();

        // Format dates to DD/MM/YYYY for display
        $rental->start_date_formatted = $rental->start_date ? $rental->start_date->format('d/m/Y') : '';
        $rental->end_date_formatted = $rental->end_date ? $rental->end_date->format('d/m/Y') : '';

        return view('rentals.edit', compact('rental', 'users', 'vms', 'admins'));
    }

    public function update(Request $request, Rental $rental)
    {
        $request->validate([
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'status' => 'required',
            'admin_id' => 'required',
            'vm_username' => 'nullable|string|max:255',
            'vm_password' => 'nullable|string|max:255',
            'vm_ip_address' => 'nullable|string|max:255',
        ]);

        $data = $request->all();

        // Parse DD/MM/YYYY to Y-m-d format for database
        try {
            $data['start_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
            $data['end_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Format tanggal tidak valid. Gunakan format DD/MM/YYYY'])->withInput();
        }

        // Validate that end_date is after or equal to start_date
        if ($data['end_date'] < $data['start_date']) {
            return back()->withErrors(['end_date' => 'Tanggal selesai harus lebih besar atau sama dengan tanggal mulai'])->withInput();
        }

        // Recalculate status based on edited dates when admin updates
        try {
            $start = \Carbon\Carbon::parse($data['start_date']);
            $end = \Carbon\Carbon::parse($data['end_date']);
            $now = \Carbon\Carbon::now();

            if ($end->lessThan($now->startOfDay())) {
                // already past -> expired
                $data['status'] = 'expired';
            } elseif ($start->greaterThan($now)) {
                // start is in future -> pending/scheduled
                $data['status'] = 'pending';
            } else {
                // between start and end -> active
                $data['status'] = 'active';
            }
        } catch (\Exception $e) {
            // If parsing fails, fall back to provided status
        }

        $rental->update($data);

        return redirect()->route('rentals.index')->with('success', 'Rental berhasil diperbarui.');
    }

    public function destroy(Rental $rental)
    {
        $rental->delete();
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
