<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Vm;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Statistik dasar untuk dashboard user
        $stats = [
            'my_vms' => Vm::where('user_id', $userId)->count(),
            'active_rentals' => Rental::where('user_id', $userId)
                ->where('status', 'active')
                ->count(),
            'total_spent' => Rental::where('user_id', $userId)->sum('total_cost'),
        ];

        // Daftar rental milik user
        $myRentals = Rental::with('vm')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return view('user.dashboard', compact('stats', 'myRentals'));
    }

    public function profile()
    {
        $user = Auth::user(); // ambil data user login
        return view('user.profile', compact('user'));
    }
}
