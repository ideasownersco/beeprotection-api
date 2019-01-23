<?php

namespace App\Models;

use App\Events\StoppedDriving;
use App\Events\StoppedWorking;
use App\Events\StartedWorking;
use App\Events\StartedDriving;
use App\Events\StartTracking;
use App\Exceptions\AssignOrderFailedException;
use Carbon\Carbon;

class Job extends BaseModel
{
    protected $table = 'jobs';
    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function photos()
    {
        return $this->hasMany(JobPhoto::class);
    }

    public function startDriving()
    {
        $this->update([
            'status'     => 'driving',
            'started_driving_at' => Carbon::now(),
            'stopped_driving_at'   => null
        ]);
        event(new StartedDriving($this));
        return $this;
    }

    public function stopDriving()
    {
        $this->update([
            'status'   => 'reached',
            'stopped_driving_at' => Carbon::now()
        ]);
        event(new StoppedDriving($this));
    }

    public function startWorking()
    {
        $this->update([
            'status'     => 'working',
            'started_working_at' => Carbon::now(),
            'stopped_working_at'   => null
        ]);
        event(new StartedWorking($this));
        return $this;
    }

    public function stopWorking()
    {
        $this->update([
            'status'   => 'completed',
            'stopped_working_at' => Carbon::now()
        ]);
        event(new StoppedWorking($this));
    }

    /**
     * @param null $newDriverID
     * @param bool $forceAssign
     * @return Job
     * @throws AssignOrderFailedException
     */
    public function assignDriver($newDriverID = null, $forceAssign = false)
    {

        $job = $this;
        /**
         * do not assign driver to a completed job
         */
        if ($job->status === 'completed') {
            throw new AssignOrderFailedException(__('general.order_already_completed'));
        }

        /**
         * only allow if force assigned, to avoid accidental assigning a driver for the order
         * which already been assigned to the driver
         */
        if ($job->status === 'working') {
            if (!$forceAssign) {
                throw new AssignOrderFailedException(__('general.order_already_on_progress'));
            }
        }

        if($newDriverID) {
            if($newDriverID === $job->driver->id) {
                throw new AssignOrderFailedException(__('Cannot assign to the same driver'));
            }
//            $job->driver->decrement('job_count');
            //@todo decrement
            $job->driver->deAssignFromJob($job->order);
        }

        $job->update([
            'driver_id' => $newDriverID ? $newDriverID: $job->driver->id
        ]);

        $job->driver->assignForJob($job->order,$newDriverID);

        return $this;
    }

    public function getStoppedWorkingAtFormattedAttribute()
    {
        if($this->stopped_working_at) {
            return Carbon::parse($this->stopped_working_at)->format('g:i a');
        }
        return null;
    }

    public function getStartedWorkingAtFormattedAttribute()
    {
        if($this->started_working_at) {
            return Carbon::parse($this->started_working_at)->format('g:i a');
        }
        return null;
    }

    public function getStartedDrivingAtFormattedAttribute()
    {
        if($this->started_driving_at) {
            return Carbon::parse($this->started_driving_at)->format('g:i a');
        }
        return null;
    }

    public function getStoppedDrivingAtFormattedAttribute()
    {
        if($this->stopped_driving_at) {
            return Carbon::parse($this->stopped_driving_at)->format('g:i a');
        }
        return null;
    }

    public function cancelled_jobs()
    {
        return $this->hasMany(CancelledJob::class,'job_id');
    }

    public function getCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    public function getDrivingAttribute()
    {
        return $this->status === 'driving';
    }

    public function getButtonNameAttribute()
    {
        $status = $this->status;

        switch ($status) {
            case 'pending':
                return 'danger';
            case 'driving':
                return 'warning';
            case 'working':
            case 'reached':
                return 'info';
            case 'completed':
                return 'success';
            case 'cancelled':
                return 'warning';
        }

    }
}
