<?php

namespace App\Events;

use App\Models\Job;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StoppedWorking
{
    use Dispatchable, SerializesModels;
    public $job;

    /**
     * Create a new event instance.
     *
     * @param $job
     */
    public function __construct(Job $job)
    {
        $this->job = $job;
    }

}
