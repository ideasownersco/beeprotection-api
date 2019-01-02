<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Twilio\Rest\Client;

class SendAuthCodeSMS implements ShouldQueue
{
    use InteractsWithQueue;

    public $connection = 'database';
    public $queue = 'registration_sms';
    public $tries = 5;

    public function handle(UserRegistered $event)
    {

        $sid = env('TWILIO_USERNAME');
        $token = env('TWILIO_PASSWORD');
        $fromPhone = env('TWILIO_PHONE_NUMBER');

        $toPhone = $event->user->mobile;

        $toPhone = '+965'.$toPhone;

        $messageBody = "BeeProtection : Your Registration code is ".$event->user->registration_code;

        $client = new Client($sid, $token);

        $client->messages->create(
            $toPhone,
            [
                'from' => $fromPhone,
                'body' => $messageBody,
            ]
        );

    }
}
