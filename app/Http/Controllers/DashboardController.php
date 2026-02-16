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

        $stats = [
            'total_vms' => $totalVms,
            'available_vms' => isset($statusCounts['available']) ? (int)$statusCounts['available'] : 0,
            'rented_vms' => isset($statusCounts['rented']) ? (int)$statusCounts['rented'] : 0,
            'maintenance_vms' => isset($statusCounts['maintenance']) ? (int)$statusCounts['maintenance'] : 0,
            'offline_vms' => isset($statusCounts['offline']) ? (int)$statusCounts['offline'] : 0,
            'active_rentals' => VMRental::where('status', 'active')->count(),
            'total_revenue' => VMRental::sum('total_cost'),
            'total_users' => User::count()
        ];

        // Exclude VMs that are not assigned to any server (prevent showing orphan VMs)
        $recentVMs = VM::whereNotNull('server_id')
            ->with(['server', 'category', 'specification'])
            ->latest()
            ->take(5)
            ->get();
        // Combine active rentals from both VMRental and Rental so admin dashboard
        // shows all renter records regardless of which table they are stored in.
        $vmRentals = VMRental::where('status', 'active')
            ->with(['vm', 'user'])
            ->latest()
            ->take(10)
            ->get();

        $otherRentals = Rental::where('status', 'active')
            ->with(['vm', 'user'])
            ->latest()
            ->take(10)
            ->get();

        // Normalize rentals: ensure start_time/end_time are Carbon instances and total_cost exists
        $normalizedVm = $vmRentals->map(function ($r) {
            // VMRental likely already has start_time/end_time as datetimes
            $r->start_time = $r->start_time ?? ($r->start_date ?? null);
            $r->end_time = $r->end_time ?? ($r->end_date ?? null);
            $r->total_cost = $r->total_cost ?? 0;
            return $r;
        });

        $normalizedOther = $otherRentals->map(function ($r) {
            $r->start_time = isset($r->start_date) ? \Carbon\Carbon::parse($r->start_date) : null;
            $r->end_time = isset($r->end_date) ? \Carbon\Carbon::parse($r->end_date) : null;
            $r->total_cost = $r->total_cost ?? 0;
            return $r;
        });

        $activeRentals = $normalizedVm->concat($normalizedOther)
            ->sortByDesc(function ($r) {
                return $r->start_time ? $r->start_time->timestamp : 0;
            })
            ->take(10)
            ->values();

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
