<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DriverResource;
use App\Http\Resources\OrdersResource;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Timing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DriversController extends Controller
{
    /**
     * @var job
     */
    private $orderModel;
    /**
     * @var Driver
     */
    private $driverModel;
    /**
     * @var Timing
     */
    private $timingModel;

    /**
     * OrdersController constructor.
     * @param Order $orderModel
     * @param Driver $driverModel
     * @param Timing $timingModel
     */
    public function __construct(Order $orderModel, Driver $driverModel, Timing $timingModel)
    {
        $this->orderModel = $orderModel;
        $this->driverModel = $driverModel;
        $this->timingModel = $timingModel;
    }

    public function getDetails($driverID)
    {
        $driver = $this->driverModel->with(['user'])->find($driverID);

        $today = Carbon::today()->toDateString();

        $workingOrder = $this->orderModel
            ->whereHas('job',function($q) use ($driver) {
                $q
                    ->where('driver_id',$driver->id)
                    ->ofStatus('working')
                ;
            })
            ->with(['job','address', 'packages.category','services.package'])
            ->whereDate('date',$today)
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->limit(1)
            ->first()
        ;

        $upcomingOrders = $this->orderModel
            ->whereHas('job', function ($q) use ($driver) {
                $q
                    ->where('driver_id', $driver->id)
                    ->ofStatus('pending')
                ;
            })
            ->with(['job', 'address', 'packages.category', 'services.package'])
            ->whereDate('date', '>=', $today)
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->limit(10)
            ->get()
        ;

        return (new DriverResource($driver))->additional([
            'success' => true,
            'working_order' => $workingOrder ? new OrdersResource($workingOrder) : null,
            'upcoming_orders' => $upcomingOrders
        ]);

    }

    public function getCompanyDrivers()
    {
        $drivers = $this->driverModel->with(['user'])->get();

        return response()->json(['success'=>true,'data'=>DriverResource::collection($drivers)]);
    }

    public function assignOrderToDriver($orderID,Request $request)
    {

        $validation = Validator::make($request->all(), [
            'driver_id'    => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => $validation->errors()->first()], 422);
        }

        $order = $this->orderModel->with(['job.driver'])->find($orderID);

        $driver = $this->driverModel->find($request->driver_id);

        if(!$driver->active || $driver->offline) {
            return response()->json(['success'=>false,'message' => __('general.driver_busy')]);
        }

        try {
            $order->job->assignDriver($request->driver_id,$request->has('force_assign'));
            $order->load('job.driver');
        } catch (\Exception $e) {
            return response()->json(['success'=>false,'message' => $e->getMessage()]);
        }

        return response()->json(['success'=>true,'data' => $order]);
    }

    public function updateDriver(Request $request)
    {
        $driver = $this->driverModel->find($request->driver_id);

        if($request->has('status')) {
            $driver->offline = !$request->status;
        }

        if($request->has('start_time_id')) {
            $time =  $this->timingModel->find($request->start_time_id);
            if($time) {
                $driver->start_time = Carbon::parse($time->name_en)->toTimeString();
            }
        }

        if($request->has('end_time_id')) {
            $time =  $this->timingModel->find($request->end_time_id);
            if($time) {
                $driver->end_time = Carbon::parse($time->name_en)->toTimeString();
            }
        }

        $driver->save();

        return response()->json(['success'=>true,'data'=>new DriverResource($driver)]);

    }
}

