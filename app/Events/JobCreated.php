<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobCreated
{
    use Dispatchable, SerializesModels;
    public $job;

    /**
     * Create a new event instance.
     *
     * @param $job
     */
    public function __construct($job)
    {
        $this->job = $job;
    }

}
