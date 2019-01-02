<?php

namespace App\Jobs;

use Carbon\Carbon;
use Davibennun\LaravelPushNotification\Facades\PushNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var array
     */
    private $deviceTokens;
    /**
     * @var string
     */
    private $message;

    public $tries = 3;

    private $payload;
    /**
     * Create a new job instance.
     *
     * @param array $deviceTokens
     * @param string $message
     * @param array $payload
     */
    public function __construct(string $message,array $deviceTokens,$payload = [])
    {
        //
        $this->message = $message;
        $this->deviceTokens = $deviceTokens;
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tokens = [];
        foreach($this->deviceTokens as $token) {
            $tokens[] = PushNotification::Device($token);
        }

        $message = PushNotification::message($this->message,['custom'=>$this->payload]);
        $devices = PushNotification::DeviceCollection($tokens);
        PushNotification::app('ios')
            ->to($devices)
            ->send($message);
    }
}
