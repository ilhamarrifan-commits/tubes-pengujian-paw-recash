<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== $role) {
            // Special case: Admin can access manager routes
            if ($role === 'manager' && Auth::user()->role === 'admin') {
                return $next($request);
            }

            // Redirect to their appropriate dashboard if they try to access wrong area
            if (Auth::user()->role === 'manager' || Auth::user()->role === 'admin') {
                return redirect()->route('manager.dashboard');
            }
            return redirect()->route('cashier.dashboard');
        }

        return $next($request);
    }
}
