<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Twilio\Rest\Client;

class SendPaymentReceiptSMS implements ShouldQueue
{
    use InteractsWithQueue;

    public $connection = 'database';
    public $tries = 3;

    public function handle(OrderCreated $event)
    {

        if (app()->env === 'production') {
            $sid = env('TWILIO_USERNAME');
            $token = env('TWILIO_PASSWORD');
            $fromPhone = env('TWILIO_PHONE_NUMBER');

            $toPhone = $event->order->user->mobile;

            $toPhone = '+965' . $toPhone;
            $messageBody = "Your order has been placed and scheduled on " . $event->order->scheduled_time . ' at '.$event->order->address->area->name;
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

}
