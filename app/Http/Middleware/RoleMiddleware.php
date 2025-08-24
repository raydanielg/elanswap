<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:admin') or 'role:superadmin'
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // superadmin passes admin checks too if needed
        if ($role === 'admin') {
            if (!in_array($user->role, ['admin', 'superadmin'], true)) {
                abort(403, 'Unauthorized');
            }
        } elseif ($role === 'superadmin') {
            if ($user->role !== 'superadmin') {
                abort(403, 'Unauthorized');
            }
        } else {
            // role:user or any other: only ensure authenticated
        }

        return $next($request);
    }
}
