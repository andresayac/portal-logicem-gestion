<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\sendSmsOtp;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;



class sendSmsToUser implements ShouldQueue
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
            // Enviar SMS usando la API de Razorpay
            $response = Http::withBasicAuth(config('app.sms_labsmobile.sms_user'), config('app.sms_labsmobile.sms_password'))
                ->post(
                    'https://api.labsmobile.com/json/send',
                    [
                        'message' => $this->data['message'],
                        "recipient" => [
                            [
                                "msisdn" => $this->data['phone']
                            ]
                        ]
                    ]
                );

            if ($response->status() !== 200) {
                throw new \Exception('Error al enviar el SMS');
            }

            $response = $response->json();

            if ($response['code'] !== '0') {
                throw new \Exception('Error al enviar el SMS');
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->fail($e);
        }
    }
}
