<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileCompleted
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

        // Skip for admins/superadmins
        if (in_array($user->role ?? 'user', ['admin', 'superadmin'], true)) {
            return $next($request);
        }

        // Determine if profile is completed
        $completed = $user->region_id && $user->district_id && $user->category_id && $user->station_id;
        if ($completed) {
            return $next($request);
        }

        // Allow access to profile routes and auth/verification/logout while incomplete
        $route = $request->route();
        $name = $route?->getName();
        $path = $request->path();

        $allowedNames = [
            'profile.edit', 'profile.update', 'logout',
            'profile.regions', 'profile.districts', 'profile.categories', 'profile.stations',
            'verification.notice', 'verification.send', 'verification.verify',
        ];

        if (($name && (in_array($name, $allowedNames, true) || str_starts_with($name, 'verification.'))) ||
            str_starts_with($path, 'profile')) {
            return $next($request);
        }

        return redirect()->route('profile.edit')->with('status', 'Tafadhali kamilisha profaili yako.');
    }
}
