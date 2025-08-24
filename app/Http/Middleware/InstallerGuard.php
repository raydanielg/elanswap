<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class InstallerGuard
{
    public function handle(Request $request, Closure $next): Response
    {
        $enabled = filter_var(config('app.installer_enabled', env('INSTALLER_ENABLED', false)), FILTER_VALIDATE_BOOLEAN);
        $installed = Storage::disk('local')->exists('installed');

        if (!$enabled || $installed) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
