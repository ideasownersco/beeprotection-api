<?php

namespace App\Listeners;

use App\Events\UserActivated;
use App\Events\UserActivatedSMS;
use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Twilio\Rest\Client;

class SendUserSMS implements ShouldQueue
{
    use InteractsWithQueue;

    public $connection = 'database';
    public $queue = 'activate_sms';
    public $tries = 5;

    public function handle(UserActivatedSMS $event)
    {

        $sid = env('TWILIO_USERNAME');
        $token = env('TWILIO_PASSWORD');
        $fromPhone = env('TWILIO_PHONE_NUMBER');

        $toPhone = $event->user->mobile;
//        $password = $event->password;
        $toPhone = '+965'.$toPhone;

        $messageBody = "تم تفعيل جميع الحسابات 
يرجى استعمال كلمة المرور المرسلة 
في الرسالة السابقة
وبريدكم الإلكتروني المسجل 

Beeprotection";
//        $messageBody = "Your account has been activated, your password is ".$password;

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
