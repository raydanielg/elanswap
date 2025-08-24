<?php

namespace App\Providers;

use App\Models\Log as ActivityLog;
use App\Services\SmsService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SmsService::class, function ($app) {
            return new SmsService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Capture user login events to activity logs
        Event::listen(Login::class, function (Login $event) {
            $user = $event->user;
            $request = request();

            ActivityLog::create([
                'user_id' => $user->id ?? null,
                'record_date' => now()->toDateString(),
                'text' => 'User login',
                'status' => 'success',
                'log_type' => 'login',
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
            ]);
        });
    }
}
