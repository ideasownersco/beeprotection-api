<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrdersResource;
use App\Models\Job;
use App\Models\Order;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    /**
     * @var Order
     */
    private $orderModel;
    /**
     * @var Job
     */
    private $jobModel;

    /**
     * OrdersController constructor.
     * @param Order $orderModel
     * @param Job $jobModel
     */
    public function __construct(Order $orderModel, Job $jobModel)
    {
        $this->orderModel = $orderModel;
        $this->jobModel = $jobModel;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * Get Order That is Currently worked
     */
    public function getWorkingOrder()
    {
        $driver = Auth::guard('api')->user()->driver;

        $today = Carbon::today()->toDateString();

        $order = $this->orderModel
            ->whereHas('job', function ($q) use ($driver) {
                $q
                    ->where('driver_id', $driver->id)
                    ->where(function($query) {
                        $query
                            ->where('status', 'working')
                            ->orWhere('status','reached')
                            ->orWhere('status','driving')
                        ;
                    })
                ;
            })
            ->with(['job', 'address', 'packages.category', 'services.package'])
            ->whereDate('date', $today)
            ->orderBy('time', 'asc')
            ->limit(1)
            ->first()
        ;

        if (!$order) {

            $order = $this->orderModel
                ->whereHas('job', function ($q) use ($driver) {
                    $q
                        ->where('driver_id', $driver->id)
                        ->where('status', 'pending')
                    ;
                })
                ->with(['job', 'address', 'packages.category', 'services.package'])
                ->whereDate('date', $today)
                ->orderBy('time', 'asc')
                ->limit(1)
                ->first();
        }

        if($order) {
            return response()->json(['success' => true, 'data' => new OrdersResource($order)]);
        } else {
            return response()->json(['success' => false, 'message' => 'no records']);
        }

    }

    public function getUpcomingOrders()
    {

        $driver = Auth::guard('api')->user()->driver;

        $today = Carbon::today()->toDateString();

        $orders = $this->orderModel
            ->whereHas('job', function ($q) use ($driver) {
                $q
                    ->where('driver_id', $driver->id)
                    ->where('status', 'pending')
                ;
            })
            ->with(['job', 'address', 'packages.category', 'services.package'])
            ->whereDate('date', $today)
            ->orderBy('time', 'asc')
            ->paginate(50)
        ;

        return response()->json(['success' => true, 'data' => OrdersResource::collection($orders)]);
    }

    public function getPastOrders()
    {
        $driver = Auth::guard('api')->user()->driver;

        $orders = $this->orderModel
            ->whereHas('job', function ($q) use ($driver) {
                $q
                    ->where('driver_id', $driver->id)
                    ->ofStatus('completed');
            })
            ->ofStatus('success')
            ->with(['job', 'address', 'packages.category', 'services.package'])
            ->orderBy('date', 'desc')
            ->orderBy('time', 'asc')
            ->latest()
            ->paginate(50)
        ;
        return OrdersResource::collection($orders)->additional(['success' => true]);
    }

    public function getDetail($orderID)
    {
        $order = $this->orderModel->find($orderID);
        if ($order) {
            $order->load(['user','address', 'packages.category', 'services.package', 'job.driver']);
            return ['success' => true, 'data' => new OrdersResource($order)];
        } else {
            return ['success' => false, 'message' => 'Unknown Order'];
        }
    }

    /**
     * @param $orderID
     *
     * PRINT PDF and SEND FILE URL
     *
     */
    public function printInvoice($orderID)
    {
        $order = $this->orderModel->with(['packages','services'])->find($orderID);
        $services = $order->services->pluck('id');


        $pdfName = 'invoice'.$order->id.'.pdf';
        $downloadPath = public_path('/invoices/'.$pdfName);
        $fullUrl = url('/invoices/'.$pdfName);

        if(file_exists($downloadPath)) {
            return response()->json(['success'=>true,'url' => $fullUrl ]);
        }

        $pdf = PDF::
        setOptions([
            'dpi' => 200,
            'defaultFont' => 'sans-serif',
            'enable-javascript' => true,
            'images' => true,
        ])
            ->setPaper('a4', 'portrait')
            ->loadView('partials.invoice_plain', compact('order','services'));

        $pdf->save($downloadPath);

        return response()->json(['success'=>true,'url' => $fullUrl ]);
    }
}

