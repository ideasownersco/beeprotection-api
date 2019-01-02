<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Driver extends BaseModel
{
    protected $table = 'drivers';
    protected $guarded = ['id'];
    protected $hidden = ['user_id'];
    protected $transitTime = 30;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function job_counts()
    {
        return $this->hasMany(JobCount::class,'driver_id');
    }

    public function blocked_dates()
    {
        return $this->hasMany(BlockedDate::class);
    }

    public function assignForJob(Order $order,$newDriverID = null)
    {
        $driver = $newDriverID ? $this->find($newDriverID): $this ;
        $driver->incrementJobCount($order->date);
        $order->blockDate($driver);
        return $this;
    }


    public function deAssignFromJob(Order $order)
    {
        $driver = $this ;
        $driver->decrementJobCount($order->date);
//        $order->blockDate($driver);
        return $this;
    }


    /**
     * @param $date
     * @param $time
     * @param $duration
     * @return array of blocked drivers
     */
    private function getBlockedDrivers($date, $time, $duration)
    {
        $orderDate = $date;
        $timeFrom = $time;
        $timeTo = $duration;

        $blockedDrivers = DB::table('blocked_dates as bd')
            ->join('drivers as d', 'd.id', '=', 'bd.driver_id')
            ->where(function ($query) use ($orderDate, $timeFrom, $timeTo) {
                $query
                    ->whereBetween('bd.from', [$timeFrom, $timeTo])
                    ->orWhereBetween('bd.to', [$timeFrom, $timeTo])
                    ->orWhere(function ($query) use ($timeFrom, $timeTo) {
                        $query
                            ->whereTime('bd.from', '<=', $timeFrom)
                            ->whereTime('bd.to', '>=', $timeTo);
                    });
            })
            ->whereDate('bd.date', $orderDate)
            ->groupBy('d.id')
            ->select('d.id')
            ->pluck('d.id')
            ->toArray();

        return $blockedDrivers;

    }

    public function getEarliestFreeTime(Order $order)
    {
        $orderDate = $order->date;
        $getEarliestFreeDriver = DB::table('blocked_dates as bd')
            ->join('drivers as d', 'd.id', '=', 'bd.driver_id')
            ->whereDate('bd.date',$orderDate)
            ->whereTime('bd.to','>',Carbon::now()->addMinutes(30)->toTimeString())
            ->orderBy('bd.to','asc')
            ->limit(5)
            ->pluck('bd.to')
        ;
        return $getEarliestFreeDriver;
    }

    public function getAvailableDrivers($date,$timeFrom,$timeTo)
    {
        $driverModel = $this;
        $blockedDrivers = $driverModel->getBlockedDrivers($date,$timeFrom,$timeTo);
        $drivers = $driverModel
            ->online()
            ->active()
            ->whereNotIn('id', $blockedDrivers)
            ->whereTime('start_time', '<=',$timeFrom)
            ->whereTime('end_time', '>=',$timeTo)
            ->select('id')
        ;

        $jobCountDrivers = JobCount::where('date',$date)->pluck('driver_id');

        $availableDrivers = $drivers->get()->pluck('id')->diff($jobCountDrivers);

        if(!$availableDrivers->count()) {
            $availableDrivers = Driver::whereIn('drivers.id',$drivers->get()->pluck('id'))
                ->join('job_counts','drivers.id','job_counts.driver_id')
                ->where('job_counts.date',$date)
                ->orderBy('job_counts.count','ASC')
                ->select('drivers.*','job_counts.count')
                ;
            return $availableDrivers;
        }

        $availableDrivers = Driver::whereIn('id',$availableDrivers)->inRandomOrder();

        return $availableDrivers;
    }

    public function getAvailableDriver($date,$timeFrom,$timeTo)
    {
        return  $this->getAvailableDrivers($date,$timeFrom,$timeTo)->first();
    }

    public function getStartTimeAttribute()
    {
        return Carbon::parse($this->attributes['start_time'])->format('g:ia');
    }

    public function getEndTimeAttribute()
    {
        return Carbon::parse($this->attributes['end_time'])->format('g:ia');
    }

    public function scopeOnline($query)
    {
        return $query->where('offline',0);
    }

    public function scopeOffline($query)
    {
        return $query->where('offline',1);
    }

    public function incrementJobCount($date)
    {

        $jobCount = $this->job_counts()->where('date',$date)->first();

        if($jobCount) {
            $jobCount->increment('count');
        } else {
            $jobCount = $this->job_counts()->create(['date' => $date]);
        }

        return $jobCount;

    }

    public function decrementJobCount($date)
    {

        $jobCount = $this->job_counts()->where('date',$date)->first();

        if($jobCount) {
            $jobCount->decrement('count');
        }

        return $jobCount;

    }
}
