<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrdersCollection;
use App\Http\Resources\OrdersResource;
use App\Http\Resources\UserResource;
use App\Models\Bid;
use App\Models\Company;
use App\Models\Customer;
use App\Models\job;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{

    private $orderModel;

    public function __construct(Order $orderModel)
    {
        $this->orderModel = $orderModel;
    }

    public function index()
    {
        //@todo:get only standing orders
        $orders = $this->orderModel
            ->with(['job.driver.user','address.area','services','packages.category','user'])
            ->success()
            ->latest()
            ->limit(50)
            ->get();
        return OrdersResource::collection($orders)->additional(['success' => true]);
    }

    public function getWorkingOrders()
    {
        $today = Carbon::today()->toDateString();
        $orders = $this->orderModel
            ->with(['job.driver.user.driver','address.area','services.package','packages.category','user.driver'])
            ->whereHas('job', function ($q) {
                $q
                    ->where('status', 'working')
                    ->orWhere('status','reached')
                    ->orWhere('status','driving')
                ;
            })
            ->success()
            ->whereDate('date', $today)
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->paginate(100)
        ;

        return OrdersResource::collection($orders)->additional(['success' => true]);
    }

    public function getUpcomingOrders()
    {
        $today = Carbon::today()->toDateString();
        $orders = $this->orderModel
            ->with(['job.driver.user.driver','address.area','services.package','packages.category','user.driver'])
            ->whereHas('job', function ($q) {
                $q->ofStatus('pending');
            })
            ->success()
            ->whereDate('date', $today)
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->paginate(100);
        return OrdersResource::collection($orders)->additional(['success' => true]);
    }

    public function getPastOrders()
    {
        $orders = $this->orderModel
            ->with(['job.driver.user.driver','address.area','services.package','packages.category','user.driver'])
            ->whereHas('job', function ($q) {
                $q->ofStatus('completed');
            })
            ->success()
            ->latest()
            ->paginate(100);

        return OrdersResource::collection($orders)->additional(['success' => true]);
    }

    public function getDetail($orderID)
    {
        $order = $this->orderModel->find($orderID);

        if(!$order) {
            return response()->json(['success'=>false,'message' => 'Invalid Order']);
        }

//        $order->load(['job.driver.user','address.area','services.package','packages.category','user.driver']);

        $order->load(['user.driver','address', 'packages.category', 'services.package', 'job.driver']);

        return response()->json(['success' => true, 'data' => new OrdersResource($order)]);
    }

    public function cancelOrder($orderID)
    {
        $order = $this->orderModel->find($orderID);
        $order->cancel();

        return response()->json(['success' => true, 'data' => new OrdersResource($order)]);
    }

}

