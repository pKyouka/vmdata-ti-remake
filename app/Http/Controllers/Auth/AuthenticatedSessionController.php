<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // If the intended URL points to a dashboard path, clear it so we use the
        // role-based default instead. This prevents non-admins from being sent
        // to the admin dashboard when /dashboard is the intended URL.
        $intended = $request->session()->get('url.intended');
        if ($intended && str_contains($intended, '/dashboard')) {
            $request->session()->forget('url.intended');
        }

        // Redirect users based on role. Preserve other intended URLs if present.
        $user = Auth::user();
        $default = route('dashboard', absolute: false);

        if (optional($user)->role === 'admin' && Route::has('admin.dashboard')) {
            $default = route('admin.dashboard', absolute: false);
        } elseif (optional($user)->role === 'user' && Route::has('user.dashboard')) {
            $default = route('user.dashboard', absolute: false);
        }

        return redirect()->intended($default);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
