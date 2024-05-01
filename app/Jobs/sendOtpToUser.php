<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\sendOTP;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;


class sendOtpToUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public $backoff = 1 * 60 * 10;

    protected $data;


    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Notification::route('mail', $this->data['email'])->notifyNow(new sendOTP($this->data));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->fail($e);
        }
    }
}
