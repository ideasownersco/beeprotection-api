<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\DriverIsBusyException;
use App\Http\Controllers\Controller;
use App\Http\Resources\DriverResource;
use App\Http\Resources\DriversCollection;
use App\Models\Driver;
use App\Models\Job;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DriversController extends Controller
{

    /**
     * @var Driver
     */
    private $driverModel;
    /**
     * @var Order
     */
    private $orderModel;

    /**
     * OrdersController constructor.
     * @param Driver $driverModel
     * @param Order $orderModel
     */
    public function __construct(Driver $driverModel,Order $orderModel)
    {
        $this->driverModel = $driverModel;
        $this->orderModel = $orderModel;
    }


    public function index(Request $request)
    {
        $drivers = $this->driverModel
            ->query()
            ->when($request->status, function ($q) use ($request) {
                $q->ofStatus($request->status);
            })
            ->paginate(10);


        return DriverResource::collection($drivers)->additional(['success' => true]);

    }

    public function assignOrderToDriver($orderID,Request $request)
    {

        $validation = Validator::make($request->all(), [
            'driver_id'   => 'required|exists:drivers,id',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => $validation->errors()->first()], 422);
        }

        $order = $this->orderModel->with(['job'])->findOrFail($orderID);

        try {
            $job = $order->assignToDriver($request->driver_id);
        } catch (DriverIsBusyException $e) {
            return response()->json(['success' => false, 'message' => trans('general.driver_busy'),'type'=>'driver_busy']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => trans('general.unknown_error'),'type'=>'driver_busy']);
        }

        return (new DriverResource($order))->additional(['success'=>true]);

    }

}

