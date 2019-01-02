<?php

namespace App\Listeners;

use App\Events\UserActivated;
use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Twilio\Rest\Client;

class SendPassCodeSMS implements ShouldQueue
{
    use InteractsWithQueue;

    public $connection = 'database';
    public $queue = 'activate_sms';
    public $tries = 5;

    public function handle(UserActivated $event)
    {

        $sid = env('TWILIO_USERNAME');
        $token = env('TWILIO_PASSWORD');
        $fromPhone = env('TWILIO_PHONE_NUMBER');

        $toPhone = $event->user->mobile;
        $password = $event->password;

        $toPhone = '+965'.$toPhone;

        $messageBody = "BeeProtection : Your account got activated, login with your email " . $event->user->email. " and password ".$password;

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
