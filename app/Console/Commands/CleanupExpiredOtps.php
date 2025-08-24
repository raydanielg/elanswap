<?php

namespace App\Console\Commands;

use App\Models\OtpVerification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupExpiredOtps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:cleanup';
    
    /**
     * The number of days after which to consider OTPs as expired.
     *
     * @var int
     */
    protected $expiryDays = 7;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired OTP verification records';

    /**
     * Execute the console command.
     */
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting cleanup of expired OTPs...');
        
        $cutoffDate = now()->subDays($this->expiryDays);
        
        // Delete expired OTPs that are either verified or expired
        $deleted = DB::transaction(function () use ($cutoffDate) {
            // Delete verified OTPs older than expiry days
            $verifiedCount = OtpVerification::where('is_verified', true)
                ->where('created_at', '<', $cutoffDate)
                ->delete();
                
            // Delete expired OTPs (not verified and past expiry)
            $expiredCount = OtpVerification::where('is_verified', false)
                ->where('expires_at', '<', now())
                ->delete();
                
            return $verifiedCount + $expiredCount;
        });
        
        $this->info("Successfully cleaned up {$deleted} expired OTP records.");
        
        return 0;
    }
}
