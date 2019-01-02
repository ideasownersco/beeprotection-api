<?php

namespace App\Listeners;

use App\Events\JobCreated;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;

class JobCreatedNotifications
{
    use InteractsWithQueue;
    use DispatchesJobs;

    /**
     * Handle the event.
     *
     * @param  JobCreated $event
     * @return void
     */
    public function handle(JobCreated $event)
    {
//        if (app()->env === 'local' || app()->env === 'production') {
            $this->notifyDriver($event->job);
            $this->notifyCustomer($event);
            $this->notifyAdmin($event);
//        }
    }

//    public function notifyDriver($job)
//    {
//        $pushTokens = $job->driver->user->push_tokens->pluck('token')->toArray();
//        $message = 'New order in '. optional($job->order->address)->city . ' on ' . $job->order->date .' at '. $job->order->time_formatted;
//        $job = (new PushNotificationJob($message,$pushTokens,['custom'=>['order_id'=>$job->order->id,'type'=>'job.created']]));
//        $this->dispatch($job);
//    }

    public function notifyCustomer($job)
    {
    }

    public function notifyAdmin($event)
    {

    }
}
