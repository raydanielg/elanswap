<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePaymentCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return $next($request);
        }

        // Allow admins/superadmins bypass
        if (in_array($user->role ?? 'user', ['admin', 'superadmin'], true)) {
            return $next($request);
        }

        // NEW: If profile is completed, allow access regardless of payment status
        if (method_exists($user, 'hasCompletedProfile') && $user->hasCompletedProfile()) {
            return $next($request);
        }

        // If not paid, only allow payment and profile routes
        if (! $user->hasPaid()) {
            $route = $request->route();
            $name = $route?->getName();
            $path = $request->path();

            $allowedNames = [
                'payment.index', 'payment.pay',
                'profile.edit', 'profile.update', 'logout',
            ];

            if (($name && in_array($name, $allowedNames, true)) || str_starts_with($path, 'profile') || str_starts_with($path, 'payment')) {
                return $next($request);
            }

            return redirect()->route('profile.edit')->with('status', 'Tafadhali kamilisha wasifu na kufanya malipo ili kuendelea.');
        }

        return $next($request);
    }
}
