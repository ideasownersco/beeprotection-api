<?php

namespace App\Providers;

use App\Events\JobCreated;
use App\Events\StartedDriving;
use App\Events\StoppedDriving;
use App\Events\StoppedWorking;
use App\Events\StartedWorking;
use App\Events\OrderCreated;
use App\Events\UserActivated;
use App\Events\UserActivatedSMS;
use App\Events\UserRegistered;
use App\Listeners\JobCreatedNotifications;
use App\Listeners\SendAuthCodeSMS;
use App\Listeners\SendPassCodeSMS;
use App\Listeners\SendPaymentReceiptSMS;
use App\Listeners\SendUserSMS;
use App\Listeners\StartedDrivingNotifications;
use App\Listeners\StoppedDrivingNotifications;
use App\Listeners\StoppedWorkingNotifications;
use App\Listeners\StartedWorkingNotifications;
use App\Listeners\OrderCreatedNotifications;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        OrderCreated::class   => [
            OrderCreatedNotifications::class,
//            SendPaymentReceiptSMS::class
        ],
        JobCreated::class     => [
//            JobCreatedNotifications::class
        ],
        StartedWorking::class => [
            StartedWorkingNotifications::class
        ],
        StoppedWorking::class => [
            StoppedWorkingNotifications::class
        ],
        StartedDriving::class => [
            StartedDrivingNotifications::class
        ],
        StoppedDriving::class => [
            StoppedDrivingNotifications::class
        ],
        UserRegistered::class => [
            SendAuthCodeSMS::class
        ],
        UserActivated::class => [
            SendPassCodeSMS::class
        ],
        UserActivatedSMS::class => [
            SendUserSMS::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
