<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserActivated
{
    use Dispatchable, SerializesModels;
    public $user;
    public $password;

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param $password
     */
    public function __construct($user,$password)
    {
        $this->user = $user;
        $this->password = $password;
    }

}
