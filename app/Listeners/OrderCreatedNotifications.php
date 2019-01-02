<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\PushToken;
use App\Models\User;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;

class OrderCreatedNotifications
{
    use InteractsWithQueue;
    use DispatchesJobs;

    /**
     * Handle the event.
     *
     * @param  OrderCreated $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        if (app()->env === 'production') {
            $this->notifyDriver($event->order);
//            $this->notifyCustomer($event->order);
//            $this->notifyAdmin($event->order);
        }
    }

//    public function notifyDriver($order)
//    {
//        $pushTokens = $order->job->driver->user->push_tokens->pluck('push_id')->toArray();
//        $message = 'New Order at '.$order->date_formatted . ' in '.$order->address->area->name;
//
//        new PushNotificationJob($message,$pushTokens,['order_id'=>$order->id,'type'=>'order.created']);
//    }

    public function notifyDriver($order)
    {
        $driverToken = $order->job->driver->user->push_tokens->pluck('push_id')->toArray();

        $admins = User::where('admin',1)->pluck('id')->toArray();
        $adminPushTokens = PushToken::whereIn('user_id',$admins)->pluck('push_id')->toArray();

        $pushTokens = array_values(array_unique(array_filter(array_merge($driverToken,$adminPushTokens))));

        $message = 'Order #'.$order->id . '. Order placed on '.$order->scheduled_time . ' at '.$order->address->area->name;

        OneSignalFacade::sendNotificationToUser(
            $message,$pushTokens,null,['order_id'=>$order->id,'type'=>'order.created']
        );

    }

    public function notifyCustomer($order)
    {
    }

    public function notifyAdmin($order)
    {

    }
}
