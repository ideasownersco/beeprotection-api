<?php

namespace App\Models;

use App\Events\JobCreated;
use App\Events\OrderCreated;
use App\Exceptions\DriversNotAvailableException;
use Carbon\Carbon;

class Order extends BaseModel
{
    protected $table = 'orders';
    protected $guarded = ['id'];
    protected $hidden = ['address_id', 'user_id'];
    protected $transitTime = 30;
//    protected $with = ['job.driver','address','user','packages','services'];

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function job()
    {
        return $this->hasOne(Job::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'order_packages')->withPivot('price','quantity');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'order_services')->withPivot('price');
    }

    public function blocked_date()
    {
        return $this->hasOne(BlockedDate::class);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeOnline($query)
    {
        return $query->where('offline', '!=', 1);
    }

    public function getTrackeableAttribute()
    {
        return $this->job && $this->job->status === 'driving';
    }

    public function getTimeFormattedAttribute()
    {
        return Carbon::parse($this->time)->format('g:i a');
    }

    public function getDateFormattedAttribute()
    {
        return Carbon::parse($this->date)->format('M jS D');
    }

    public function getScheduledTimeAttribute()
    {
        return Carbon::parse($this->date. ' '. $this->time)->format('d-m-Y g:i a');
    }

    public function getFullAddressAttribute()
    {
        return optional(optional($this->address)->area)->name;
    }

    public function getIsCompletedAttribute()
    {
        return optional($this->job)->status === 'completed';
    }

//    /**
//     * @return Order
//     */
//    public function delete()
//    {
//        // cancel order
//        $this->cancelJob($this->job);
////        $this->delete();
//
////        parent::delete();
//        return $this;
//    }

    /**
     * @param bool $force
     * @return Order
     * @throws DriversNotAvailableException
     * Create Job
     * @throws \Exception
     */
    public function create($force= false)
    {
        $order = $this;
        $orderDate = $order->date;
        $orderTime = $order->time;

        $dateMax = Carbon::createFromDate('2019','1','14');

        if(Carbon::parse($orderDate)->gt($dateMax)) {
            throw new \Exception('Free Wash is only until '. $dateMax->toDateString());
        }

        $driverModel = new Driver();
        $orderDuration = $order->calculateDuration();
        $driver = $driverModel->getAvailableDriver($orderDate, $orderTime, $orderDuration);

        if (!$driver) {
            // after payment success
            if($force) {
                $driver = $driverModel->where('offline',0)->where('active',1)->first();
            } else {
                throw new DriversNotAvailableException('All Drivers are Busy, Please Choose another Time');
            }
        }

        $this->createJob($driver->id);

        // create order
        $order->update([
            'status' => 'success'
        ]);

        try {
            event(new OrderCreated($order));
        } catch (\Exception $e) {

        }

        return $this;

    }

    /**
     * @param null $orderTime
     * @param null $packageDuration
     * @param null $serviceDuration
     * @param bool $freeWash
     * Calulcate Total Duration
     * @return string
     */
    public function calculateDuration($orderTime = null,$packageDuration = null,$serviceDuration = null,$freeWash = false)
    {
        $order = $this;
        $packageDuration = $packageDuration ? $packageDuration : $order->packages->sum('duration');

        $packages = $order->packages;
        foreach ($packages as $package) {
            if($package->pivot->quantity > 1) {
                $packageDuration = $packageDuration - $package->duration;
                $packageDuration += $package->duration * $this->getPackageQuantityHours($package->pivot->quantity);
            }
        }

        $serviceDuration = $serviceDuration ? $serviceDuration : $order->services->sum('duration');
        $totalDuration = $packageDuration + $serviceDuration + $this->transitTime;

        $orderTime = $orderTime ? $orderTime : $order->time;

        if($freeWash ||  $order->free_wash) {
            $totalDuration += 15;
        }

        $duration = Carbon::parse($orderTime)->addMinutes($totalDuration)->toTimeString();

        return $duration;
    }

    public function blockDate(Driver $driver)
    {
        $order = $this;

        if($order->blocked_date) {
            $order->blocked_date->delete();
        }

        $timeFrom = Carbon::parse($order->time)->addMinute(1)->toTimeString();
        $timeTo = Carbon::parse($order->calculateDuration())->subMinute(1)->toTimeString();
        $order->blocked_date()->create([
            'driver_id' => $driver->id,
            'date' => $order->date,
            'from' => $timeFrom,
            'to'   => $timeTo
        ]);
    }

    /**
     * @param $driverID
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function createJob($driverID)
    {
        $job = $this->job()->create([
            'driver_id' => $driverID,
            'order_id'  => $this->id,
            'status'    => 'pending',
        ]);

        $job->assignDriver();

        event(new JobCreated($job));

        return $job;
    }

    /**
     * @param $job
     * @return Job
     * @throws \Exception
     */
    private function cancelJob($job)
    {
        //cancel old job
        if($job) {
            $job->cancelled_jobs()->create(['job_id'=>$job->id,'driver_id' => $job->driver->id]);
            $job->delete();

            parent::delete();

            return $job;
        }
    }


    public function getPackageQuantityHours($quantity)
    {

        switch ($quantity) {
            case $quantity < 11 :
                $hourDuration = 1;
                break;
            case $quantity < 21 :
                $hourDuration = 2;
                break;
            case $quantity < 31 :
                $hourDuration = 3;
                break;
            case $quantity < 41 :
                $hourDuration = 4;
                break;
            case $quantity < 51 :
                $hourDuration = 5;
                break;
            case $quantity < 61 :
                $hourDuration = 6;
                break;
            case $quantity < 71 :
                $hourDuration = 7;
                break;
            case $quantity < 81 :
                $hourDuration = 8;
                break;
            case $quantity < 91 :
                $hourDuration = 9;
                break;
            case $quantity <= 100 :
                $hourDuration = 10;
                break;
            default :
                $hourDuration = 1;
                break;
        }

        return $hourDuration;
    }

    public function getTimeToAttribute()
    {
        return Carbon::parse($this->calculateDuration())->format('g:i a');
    }

    public function getPackageTypeAttribute()
    {
        $order = $this;
        if($order->free_wash) {
            return 'Free Wash';
        } else {
            $type = '';
            foreach($order->packages as $package) {
                $type .= optional($package->category)->name . ' ' .$package->name;
                if($order->services->count()) {
                    $type .= ' (Addons: ';
                    foreach($order->services as $service) {
                        $type .= $service->name . ', ';
                    }
                    $type .= ')';
                }
                $type .= " - ";
            }
            return $type;
        }

    }

    public function scopeToday($query)
    {
        return $query
            ->whereDate('date', Carbon::today()->toDateString());
    }

    public function scopeMonth($query)
    {
        return $query
            ->whereMonth('date', date('n'));
    }

    public function scopeYear($query)
    {
        return $query
            ->whereYear('date', date('Y'));
    }

}
