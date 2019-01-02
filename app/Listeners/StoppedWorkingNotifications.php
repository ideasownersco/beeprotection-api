<?php

namespace App\Listeners;

use App\Events\JobCreated;
use App\Events\StoppedWorking;
use App\Events\OrderCreated;
use App\Jobs\PushNotificationJob;
use App\Models\PushToken;
use App\Models\User;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Twilio\Rest\Client;

class StoppedWorkingNotifications
{
    use InteractsWithQueue;
    use DispatchesJobs;

    public function handle(StoppedWorking $event)
    {
        if (app()->env === 'production') {
            $this->notifyCustomer($event->job);
        }
    }

    public function notifyCustomer($job)
    {
        $customerToken = $job->order->user->push_tokens->pluck('push_id')->toArray();
//        $admins = User::where('admin',1)->pluck('id')->toArray();
//        $adminPushTokens = PushToken::whereIn('user_id',$admins)->pluck('push_id')->toArray();
        $pushTokens = array_values(array_unique(array_filter(array_merge($customerToken,[]))));
        $message = 'Your order has been completed. Thank you for using BeeProtection Service';
        OneSignalFacade::sendNotificationToUser(
            $message,$pushTokens,null,['order_id'=>$job->order->id,'type'=>'stopped.working']
        );
    }

//    public function notifyCustomer($job)
//    {
//        $pushTokens = $job->order->user->push_tokens->pluck('token')->toArray();
//        $message = 'Your order has been completed. Thank you for using BeeProtection.';
//        $job = (new PushNotificationJob($message,$pushTokens,['order_id'=>$job->order->id,'type'=>'stopped.working']));
//        $this->dispatch($job);
//    }
}
