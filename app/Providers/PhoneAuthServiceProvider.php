<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class PhoneAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Auth::provider('phone', function ($app, array $config) {
            return new PhoneAuthProvider($app['hash'], $config['model']);
        });
    }

    public function register()
    {
        //
    }
}
