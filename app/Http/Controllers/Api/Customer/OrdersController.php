<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrdersResource;
use App\Models\Order;
use App\Models\Package;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    /**
     * @var Order
     */
    private $orderModel;
    /**
     * @var User
     */
    private $userModel;
    /**
     * @var Package
     */
    private $packageModel;
    /**
     * @var Service
     */
    private $serviceModel;

    /**
     * OrdersController constructor.
     * @param Order $orderModel
     * @param User $userModel
     * @param Package $packageModel
     * @param Service $serviceModel
     */
    public function __construct(Order $orderModel, User $userModel, Package $packageModel, Service $serviceModel)
    {
        $this->orderModel = $orderModel;
        $this->userModel = $userModel;
        $this->packageModel = $packageModel;
        $this->serviceModel = $serviceModel;
    }

    public function getWorkingOrder()
    {
        $user = Auth::guard('api')->user();

        if($user->blocked) {
            return ['success' => false, 'message' => 'Your account has been blocked by the administrator. please contact support team'];
        }

        $today = Carbon::today()->toDateString();

        // if there is an accepted order, prefer that
        $orders = $this->orderModel
            ->whereHas('job', function ($q) {
                // do not include the orders where job has been completed
                $q
                    ->where('status', '!=', 'completed')
                ;
            })
            ->where('user_id', $user->id)
            ->with(['job', 'address', 'packages.category', 'services.package'])
            ->success()
            ->whereDate('date', '>=', $today)
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->paginate(10)
        ;

        return OrdersResource::collection($orders)->additional(['success' => true]);

    }

    public function getUpcomingOrders()
    {
        $user = Auth::guard('api')->user();

        if($user->blocked) {
            return ['success' => false, 'message' => 'Your account has been blocked by the administrator. please contact support team'];
        }

        $today = Carbon::yesterday()->toDateString();
        $orders = $this->orderModel
            ->whereHas('job', function ($q) {
                $q
                    ->where('status', '!=', 'completed')
                ;
            })
            ->with(['address', 'packages.category', 'services.package', 'job'])
            ->where('user_id', $user->id)
            ->success()
            ->whereDate('date', '>=', $today)
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->paginate(50)
        ;

        return OrdersResource::collection($orders)->additional(['success' => true]);
    }

    public function getPastOrders()
    {
        $user = Auth::guard('api')->user();

        if($user->blocked) {
            return ['success' => false, 'message' => 'Your account has been blocked by the administrator. please contact support team'];
        }

        $orders = $this->orderModel
            ->whereHas('job', function ($q) {
                $q->ofStatus('completed');
            })
            ->with(['job', 'address', 'packages.category', 'services.package'])
            ->where('user_id', $user->id)
            ->success()
            ->latest()
            ->paginate(50);

        return OrdersResource::collection($orders)->additional(['success' => true]);
    }

    public function getDetail($orderID)
    {
        $order = $this->orderModel->find($orderID);
        if($order) {
            $order->load(['address','packages.category', 'services.package', 'job.driver.user']);
            return ['success' => true, 'data' => new OrdersResource($order)];
        } else {
            return ['success' => false, 'message' => 'Invalid Order'];
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * After Payment, Set Payment Success by creating Job
     */
    public function setPaymentSuccess(Request $request)
    {
        $order = $this->orderModel->find($request->order_id);

        if($order) {
            try {
                if(!$order->job) {
                    $order->create(true);
                    return response()->json(['success'=>true, 'message' => 'Job Created']);
                }
            } catch (\Exception $e) {
                return response()->json(['success'=>false, 'message' => 'Job Creation Failed. '. $e->getMessage()]);
            }
        }

        return response()->json(['success'=>false, 'message' => 'Invalid Order']);

    }

}

