<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only count GET requests to avoid inflating via assets/forms
        if ($request->method() === 'GET') {
            $today = Carbon::today();

            $visit = Visit::firstOrCreate([
                'visited_date' => $today->toDateString(),
            ]);

            // atomic increment avoids race conditions
            $visit->increment('count');

            // Compute aggregates
            $startOfWeek = (clone $today)->startOfWeek();
            $endOfWeek = (clone $today)->endOfWeek();
            $startOfMonth = (clone $today)->startOfMonth();
            $endOfMonth = (clone $today)->endOfMonth();

            $weekCount = Visit::whereBetween('visited_date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])->sum('count');
            $monthCount = Visit::whereBetween('visited_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])->sum('count');
            $totalCount = Visit::sum('count');

            // Share to all views
            view()->share([
                'visit_total' => (int) $totalCount,
                'visit_week' => (int) $weekCount,
                'visit_month' => (int) $monthCount,
            ]);
        }

        return $next($request);
    }
}
