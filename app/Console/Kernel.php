<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Clean up expired OTPs daily at midnight
        $schedule->command('otp:cleanup')
                 ->daily()
                 ->onSuccess(function () {
                     \Illuminate\Support\Facades\Log::info('Successfully cleaned up expired OTPs');
                 })
                 ->onFailure(function () {
                     \Illuminate\Support\Facades\Log::error('Failed to clean up expired OTPs');
                 });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
