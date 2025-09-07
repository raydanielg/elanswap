<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ?int $userId;
    public ?string $phone;
    public string $text;

    /**
     * Create a new job instance.
     *
     * @param int|null $userId If provided, will use sendsms(user_id,...)
     * @param string|null $phone If provided, will use sendsms_to_number(phone,...)
     */
    public function __construct(?int $userId, ?string $phone, string $text)
    {
        $this->userId = $userId;
        $this->phone = $phone;
        $this->text = $text;
        // Optional: set a reasonable timeout and tries
        $this->onQueue('sms');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if ($this->userId && function_exists('sendsms')) {
                @\sendsms($this->userId, $this->text);
                return;
            }
            if ($this->phone && function_exists('sendsms_to_number')) {
                @\sendsms_to_number($this->phone, $this->text, null);
                return;
            }
        } catch (\Throwable $e) {
            \Log::warning('SendSms job failed: ' . $e->getMessage());
        }
    }
}
