<?php

namespace App\Http\Controllers;

use App\Models\VMRental;
use Illuminate\Http\Request;
use App\Http\Requests\StoreVMRentalRequest;
use App\Models\VM;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewVMRentalRequest;
use App\Notifications\VMRentalStatusUpdated;
use Illuminate\Validation\Rule;

class VMRentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            $rentals = VMRental::with(['user', 'vm'])->latest()->paginate(15);
        } else {
            $rentals = VMRental::with('vm')->where('user_id', $user->id)->latest()->paginate(15);
        }

        return view('vmrentals.index', compact('rentals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vms = VM::where('status', 'available')->get();
        return view('vmrentals.create', compact('vms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVMRentalRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';
        try {
            // parse start_time
            $start = \Carbon\Carbon::parse($data['start_time']);
            // if end_time not provided, default to +1 hour
            if (empty($data['end_time'])) {
                $end = $start->copy()->addHour();
                $data['end_time'] = $end->toDateTimeString();
            } else {
                $end = \Carbon\Carbon::parse($data['end_time']);
            }

            // ensure vm exists (vm_id is optional) and compute cost when possible
            $vm = null;
            if (!empty($data['vm_id'])) {
                $vm = VM::find($data['vm_id']);
            }

            if ($vm && isset($vm->specification) && isset($vm->specification->price_per_hour)) {
                $hours = max(1, $start->diffInHours($end));
                $data['total_cost'] = $hours * ($vm->specification->price_per_hour ?? 0);
            } else {
                $data['total_cost'] = 0;
            }

            // store Carbon instances (Eloquent will handle casting)
            $data['start_time'] = $start;
            $data['end_time'] = $end;

            $rental = VMRental::create($data);

            // notify all admins
            $admins = \App\Models\User::where('role', 'admin')->get();
            if ($admins->count()) {
                Notification::send($admins, new NewVMRentalRequest($rental));
            }

            return redirect()->route('vmrentals.index')->with('success', 'Permintaan sewa VM berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error('Failed to create VMRental', ['error' => $e->getMessage(), 'user_id' => Auth::id(), 'data' => $data]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $vMRental = VMRental::with(['vm', 'user'])->find($id);
        if (!$vMRental) {
            Log::warning('VMRental not found for show', ['user_id' => Auth::id() ?? null, 'rental_id' => $id, 'url' => request()->fullUrl()]);
            return redirect()->back()->with('error', 'Permintaan sewa tidak ditemukan.');
        }
        return view('vmrentals.show', ['rental' => $vMRental]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $vMRental = VMRental::find($id);
        $user = Auth::user();
        if (!$vMRental) {
            Log::warning('VMRental not found for edit', ['user_id' => $user->id ?? null, 'rental_id' => $id, 'url' => request()->fullUrl()]);
            return redirect()->back()->with('error', 'Permintaan sewa tidak ditemukan.');
        }
        if (!$user->isAdmin() && $user->id !== $vMRental->user_id) {
            Log::warning('Unauthorized edit attempt', ['user_id' => $user->id ?? null, 'rental_id' => $vMRental->id, 'url' => request()->fullUrl()]);
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengedit permintaan sewa ini.');
        }
        $vms = VM::all();
        return view('vmrentals.edit', ['rental' => $vMRental, 'vms' => $vms]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreVMRentalRequest $request, $id)
    {
        $vMRental = VMRental::find($id);
        $user = Auth::user();
        if (!$vMRental) {
            Log::warning('VMRental not found for update', ['user_id' => $user->id ?? null, 'rental_id' => $id, 'url' => request()->fullUrl()]);
            return redirect()->back()->with('error', 'Permintaan sewa tidak ditemukan.');
        }
        if (!$user->isAdmin() && $user->id !== $vMRental->user_id) {
            Log::warning('Unauthorized update attempt', ['user_id' => $user->id ?? null, 'rental_id' => $vMRental->id, 'url' => request()->fullUrl()]);
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk memperbarui permintaan sewa ini.');
        }
        $data = $request->validated();
        try {
            $start = \Carbon\Carbon::parse($data['start_time']);
            if (empty($data['end_time'])) {
                $end = $start->copy()->addHour();
                $data['end_time'] = $end->toDateTimeString();
            } else {
                $end = \Carbon\Carbon::parse($data['end_time']);
            }

            $vm = null;
            if (!empty($data['vm_id'])) {
                $vm = VM::find($data['vm_id']);
            }

            if ($vm && isset($vm->specification) && isset($vm->specification->price_per_hour)) {
                $hours = max(1, $start->diffInHours($end));
                $data['total_cost'] = $hours * ($vm->specification->price_per_hour ?? 0);
            } else {
                $data['total_cost'] = 0;
            }

            $data['start_time'] = $start;
            $data['end_time'] = $end;

            // Recalculate status based on edited times when admin updates
            try {
                $now = \Carbon\Carbon::now();
                if ($end->lt($now)) {
                    $data['status'] = 'expired';
                } elseif ($start->gt($now)) {
                    $data['status'] = 'pending';
                } else {
                    $data['status'] = 'active';
                }
            } catch (\Exception $e) {
                // leave provided status intact on failure
            }

            $vMRental->update($data);
            return redirect()->route('vmrentals.index')->with('success', 'Permintaan sewa berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Failed to update VMRental', ['error' => $e->getMessage(), 'user_id' => $user->id ?? null, 'data' => $data]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui permintaan sewa.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $vMRental = VMRental::find($id);
        $user = Auth::user();
        if (!$vMRental) {
            Log::warning('VMRental not found for delete', ['user_id' => $user->id ?? null, 'rental_id' => $id, 'url' => request()->fullUrl()]);
            return redirect()->back()->with('error', 'Permintaan sewa tidak ditemukan.');
        }

        if (!$user->isAdmin() && $user->id !== $vMRental->user_id) {
            Log::warning('Unauthorized delete attempt', ['user_id' => $user->id ?? null, 'rental_id' => $vMRental->id, 'url' => request()->fullUrl()]);
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus permintaan sewa ini.');
        }
        // allow deletion when pending or cancelled for owners; admin can delete always
        if (!$user->isAdmin() && !in_array($vMRental->status, ['pending', 'cancelled'])) {
            Log::warning('Forbidden delete attempt due to status', ['user_id' => $user->id ?? null, 'rental_id' => $vMRental->id, 'status' => $vMRental->status]);
            return redirect()->back()->with('error', 'Permintaan sewa ini tidak dapat dihapus pada status saat ini.');
        }
        $vMRental->delete();
        return redirect()->route('vmrentals.index')->with('success', 'Permintaan sewa dihapus.');
    }

    /**
     * Admin respond to a rental request (approve/reject)
     */
    public function respond(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan aksi ini.');
        }

        $vMRental = VMRental::with('user', 'vm')->find($id);
        if (!$vMRental) {
            return redirect()->back()->with('error', 'Permintaan sewa tidak ditemukan.');
        }

        $action = $request->input('action'); // expected: approve or reject
        if (!in_array($action, ['approve', 'reject'])) {
            return redirect()->back()->with('error', 'Aksi tidak valid.');
        }

        // When admin approves, redirect to VM creation form prefilled with rental requirements
        try {
            if ($action === 'approve') {
                // keep rental status as pending until VM is actually created; redirect admin to VM create
                return redirect()->route('vms.create', ['rental_id' => $vMRental->id])
                    ->with('success', 'Permintaan sewa disetujui. Silakan buat VM baru sesuai permintaan.');
            }

            // For reject, mark cancelled
            $vMRental->status = 'cancelled';
            $vMRental->save();

            // notify the user about rejection
            $vMRental->user->notify(new VMRentalStatusUpdated($vMRental, 'reject', $user));

            return redirect()->back()->with('success', 'Permintaan sewa telah ditolak.');
        } catch (\Exception $e) {
            Log::error('Failed to respond to VMRental', ['error' => $e->getMessage(), 'rental_id' => $vMRental->id ?? $id, 'action' => $action]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses aksi.');
        }
    }

    /**
     * Update VMRental status via AJAX (admin only)
     */
    public function updateStatus(Request $request, VMRental $vmrental)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'active', 'expired', 'cancelled', 'completed'])],
        ]);

        $old = $vmrental->status;
        $vmrental->status = $validated['status'];
        $vmrental->save();

        // Optionally notify the user
        try {
            $vmrental->user->notify(new VMRentalStatusUpdated($vmrental, 'update', $user));
        } catch (\Exception $e) {
            Log::warning('Failed to notify user on vmrental status change', ['error' => $e->getMessage(), 'vmrental_id' => $vmrental->id]);
        }

        return response()->json([
            'success' => true,
            'old' => $old,
            'status' => $vmrental->status,
            'message' => 'Status updated to ' . $vmrental->status,
        ]);
    }
    public function requestReset($id)
    {
        $rental = VMRental::findOrFail($id);
        $user = Auth::user();

        // Authorization: Owner or Admin
        if ($user->id !== $rental->user_id && !$user->isAdmin()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan aksi ini.');
        }

        if ($rental->status !== 'active') {
            return redirect()->back()->with('error', 'Hanya VM yang aktif yang dapat diajukan reset.');
        }

        if ($rental->reset_requested) {
            return redirect()->back()->with('info', 'Permintaan reset sudah diajukan sebelumnya.');
        }

        $rental->reset_requested = true;
        $rental->reset_requested_at = now();
        $rental->save();

        // TODO: Notification to Admin

        return redirect()->back()->with('success', 'Permintaan reset VM berhasil diajukan. Admin akan memproses permintaan Anda.');
    }
}
