<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        // Defensive: ensure role is scalar/string for comparison
        $userRole = is_scalar($user->role) ? (string) $user->role : null;

        // Log the current user role and allowed roles to help debugging
        Log::debug('RoleMiddleware: user_id=' . ($user->id ?? 'n/a') . ' role=' . var_export($userRole, true) . ' allowed=' . json_encode($roles));

        // Periksa apakah user memiliki role yang diizinkan
        if ($userRole !== null && in_array($userRole, $roles, true)) {
            return $next($request);
        }

        // Jika tidak memiliki akses, redirect atau abort
        abort(403, 'Unauthorized access. You do not have permission to access this page.');
    }
}