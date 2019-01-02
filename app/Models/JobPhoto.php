<?php

namespace App\Models;

use App\Events\StoppedDriving;
use App\Events\StoppedWorking;
use App\Events\StartedWorking;
use App\Events\StartedDriving;
use App\Events\StartTracking;
use App\Exceptions\AssignOrderFailedException;
use Carbon\Carbon;

class JobPhoto extends BaseModel
{
    protected $table = 'job_photos';
    protected $guarded = ['id'];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

}