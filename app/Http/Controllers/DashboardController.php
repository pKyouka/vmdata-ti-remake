<?php
namespace App\Http\Controllers;

use App\Models\VM;
use App\Models\VMRental;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Dashboard Admin
    public function index()
    {
        // Redirect non-admin users to the user dashboard.
        if (optional(Auth::user())->role !== 'admin') {
            return redirect()->route('user.dashboard');
        }

        // Basic stats
        $totalVms = VM::count();

        // Count VMs grouped by status so dashboard overview reflects actual VM statuses
        $statusCounts = VM::selectRaw('status, COUNT(*) as cnt')
            ->groupBy('status')
            ->pluck('cnt', 'status')
            ->toArray();

        // Calculate total revenue from rentals (includes both regular & vm_rentals via inheritance)
        $totalRevenue = \App\Models\Rental::sum('total_cost') ?? 0;

        $stats = [
            'total_vms' => $totalVms,
            'available_vms' => isset($statusCounts['available']) ? (int)$statusCounts['available'] : 0,
            'rented_vms' => isset($statusCounts['rented']) ? (int)$statusCounts['rented'] : 0,
            'maintenance_vms' => isset($statusCounts['maintenance']) ? (int)$statusCounts['maintenance'] : 0,
            'offline_vms' => isset($statusCounts['offline']) ? (int)$statusCounts['offline'] : 0,
            'active_rentals' => VMRental::where('status', 'active')->count(),
            'total_revenue' => $totalRevenue,
            'total_users' => User::count()
        ];

        // Exclude VMs that are not assigned to any server (prevent showing orphan VMs)
        $recentVMs = VM::whereNotNull('server_id')
            ->with(['server', 'category', 'specification'])
            ->latest()
            ->take(5)
            ->get();
        
        // Get all active rentals (includes both regular & vm_rentals via inheritance)
        $activeRentals = Rental::where('status', 'active')
            ->with(['vm', 'user'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($r) {
                // Normalize fields for view
                $r->start_time = $r->start_time ?? ($r->start_date ?? null);
                $r->end_time = $r->end_time ?? ($r->end_date ?? null);
                $r->total_cost = $r->total_cost ?? 0;
                $r->rental_type = $r->rental_type ?? 'regular';
                return $r;
            });


        return view('dashboard', compact('stats', 'recentVMs', 'activeRentals'));
    }

    // Dashboard User
    public function user()
    {
        $user = Auth::user();

        $stats = [
            'my_vms' => VMRental::where('user_id', $user->id)->count(),
            'active_rentals' => VMRental::where('user_id', $user->id)
                                         ->where('status', 'active')
                                         ->count(),
            'total_spent' => VMRental::where('user_id', $user->id)->sum('total_cost'),
        ];

        $myRentals = VMRental::where('user_id', $user->id)
            ->with(['vm', 'user'])
            ->latest()
            ->take(10)
            ->get();

        return view('user.dashboard', compact('stats', 'myRentals'));
    }
}
