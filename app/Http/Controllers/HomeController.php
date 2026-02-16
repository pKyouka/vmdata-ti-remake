<?php

namespace App\Http\Controllers;

use App\Models\VM;
use App\Models\Rental;
use App\Models\User;
use App\Models\Server;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'total_vms' => VM::count(),
            'available_vms' => VM::where('status', 'available')->count(),
            'active_rentals' => Rental::where('status', 'active')->count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_servers' => Server::count(),
            'total_cpu' => VM::sum('cpu'),
            'total_ram' => VM::sum('ram'),
            'total_storage' => VM::sum('storage'),
        ];

        // Get recent VMs (available)
        $recentVMs = VM::where('status', 'available')
            ->with(['category', 'server'])
            ->latest()
            ->take(6)
            ->get();

        return view('welcome', compact('stats', 'recentVMs'));
    }
}
