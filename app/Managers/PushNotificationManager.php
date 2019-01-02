<?php

namespace App\Managers;


use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Davibennun\LaravelPushNotification\PushNotification;

class PushNotificationManager
{
    /**
     * @var User
     */
    private $userModel;

    /**
     * PushNotificationManager constructor.
     */
    public function __construct()
    {
        $this->userModel = new User();
    }

    public function notify($message='',$tokens =[], $payload=[])
    {
        $job = (new SendPushNotificationsToAllDevice($tokens,$message,$payload));

    }

}