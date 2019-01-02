<?php

namespace App\Listeners;

use App\Events\StoppedDriving;
use App\Jobs\PushNotificationJob;
use App\Models\PushToken;
use App\Models\User;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;

class StoppedDrivingNotifications
{
    use InteractsWithQueue;
    use DispatchesJobs;

    public function handle(StoppedDriving $event)
    {
        if (app()->env === 'production') {
            $this->notifyCustomer($event->job);
        }
    }

    public function notifyDriver($job)
    {
    }

    public function notifyCustomer($job)
    {
        $customerToken = $job->order->user->push_tokens->pluck('push_id')->toArray();
//        $admins = User::where('admin',1)->pluck('id')->toArray();
//        $adminPushTokens = PushToken::whereIn('user_id',$admins)->pluck('push_id')->toArray();
        $pushTokens = array_values(array_unique(array_filter(array_merge($customerToken,[]))));
        $jobAddress = $job->order->address->area->name;
        $message = 'Driver has reached the order location at '.$jobAddress;

        OneSignalFacade::sendNotificationToUser(
            $message,$pushTokens,null,['order_id'=>$job->order->id,'type'=>'stopped.driving']
        );
    }

//    public function notifyCustomer($job)
//    {
//        $pushTokens = $job->order->user->push_tokens->pluck('token')->toArray();
//        $message = 'Driver has reached the order location';
//        $job = (new PushNotificationJob($message,$pushTokens,['order_id'=>$job->order->id,'type'=>'stopped.driving']));
//        $this->dispatch($job);
//    }

}
