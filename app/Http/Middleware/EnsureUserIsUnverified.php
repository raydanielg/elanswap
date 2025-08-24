<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsUnverified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        // If user is already verified, redirect to home
        if (Auth::check() && Auth::user()->hasVerifiedPhone()) {
            return redirect()->route('home');
        }

        // If there's no user ID in the session, redirect to register
        if (!$request->session()->has('otp_verification_user_id')) {
            return redirect()->route('register');
        }

        return $next($request);
    }
}
