<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Ensure only authenticated users with the 'admin' role can proceed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->guest(route('login'))->with('error', 'Please log in to continue.');
        }

        $user = Auth::user();

        // Check account status
        if ($user->status === 'suspended' || $user->status === 'blocked') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account access has been restricted. Please contact support.',
            ]);
        }

        // Strict role check: only users with admin role allowed
        if (!$user->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only administrators can access the admin dashboard.',
                ], 403);
            }

            return redirect()->route('student.dashboard')->with('error', 'Unauthorized access: The admin dashboard is restricted to administrators only.');
        }

        return $next($request);
    }
}
