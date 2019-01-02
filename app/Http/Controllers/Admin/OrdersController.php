<?php

namespace App\Http\Controllers\Admin;

use App\Core\InvoicesExport;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * @var Order
     */
    private $orderModel;
    /**
     * @var Driver
     */
    private $driverModel;

    /**
     * OrdersController constructor.
     * @param Order $orderModel
     * @param Driver $driverModel
     */
    public function __construct(Order $orderModel,Driver $driverModel)
    {
        $this->orderModel = $orderModel;
        $this->driverModel = $driverModel;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $requestedStatus = $request->has('status') ? $request->status : 'all';

        $searchQuery = $request->search;

        $orders = $this->orderModel
//            ->has('job.driver.user')
            ->with(['job.driver.user','address.area','services','packages.category','user'])
            ->success()
            ->latest()
//            ->paginate(50)
        ;

//        dd($orders);

        if($searchQuery) {
            $ids = explode(',',$searchQuery);

            $orders = $orders->whereIn('id',$ids);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $orders = $orders->whereHas('job',function($q) use ($request) {
                $q->where('status',$request->status);
            });
        }

        $orders = $orders
            ->paginate(50)
        ;

//        $todaysCount = 0;
//        $monthsCount = 0;
//        $yearsCount = 0;

        $todaysCount = $this->orderModel->success()->today()->count();
        $monthsCount = $this->orderModel->success()->month()->count();
        $yearsCount = $this->orderModel->success()->year()->count();

        $title = 'Orders';
        return view('admin.orders.index', compact('orders','title','requestedStatus','todaysCount','monthsCount','yearsCount','searchQuery'));
    }

    public function show($id)
    {
        $order = $this->orderModel
            ->with(['job.driver.user','address.area','services','packages.category','user'])
            ->find($id);

        if(!$order) {
            return redirect()->back()->with(['error' => 'Invalid Order']);
        }

        $title = $order->id;

        $availableDrivers = $this->driverModel->getAvailableDrivers($order->date,$order->time, $order->calculateDuration())->get()->pluck('id')->toArray();

        $drivers = $this->driverModel->with(['user'])
            ->whereIn('id',$availableDrivers)
            ->get();

        $driverNames = collect();


        foreach($drivers as $driver) {
            if($driver->id !== $order->job->driver->id) {
                $driverNames->push(['name'=> $driver->user->name,'id'=>$driver->id]);
            }
        }

        $driverNames = $driverNames->pluck('name','id');

        return view('admin.orders.view', compact('order','title','driverNames'));
    }

    public function assignDriver($id,Request $request)
    {
        $this->validate($request,[
            'driver_id' => 'required'
        ]);

        $order = $this->orderModel->with(['job.driver'])->find($id);

        $driver = $this->driverModel->find($request->driver_id);

        if(!$driver->active || $driver->offline) {
            return redirect()->back()->with('warning','Driver not active or offline');
        }

        try {
            $order->job->assignDriver($driver->id,true);
            $order->load('job.driver');
        } catch (\Exception $e) {

//            dd($e->getMessage());
            return redirect()->back()->with('warning','Order Failed');
        }

        return redirect()->back()->with('success','Driver assign success');

    }

    public function destroy($id)
    {
        $order = $this->orderModel->find($id);

        if(!$order) {
            return redirect()->back()->with('error','Invalid Order');
        }

        if($order->job) {
            $driver = optional($order->job)->driver;

            if($driver) {
                $jobCount = $driver->job_counts()->where('date',$order->date)->first();

                if($jobCount) {
                    $jobCount->decrement('count');
                }
            }

            $order->job()->delete();
        }

        if($order->blocked_date) {
            $order->blocked_date()->delete();
        }

        if($order->packages->count()) {
            $order->packages()->sync([]);
        }

        if($order->services->count()) {
            $order->services()->sync([]);
        }

        $order->delete();

        return redirect()->back()->with('success','Order Deleted');

    }

    public function getInvoice($id)
    {
        $order = $this->orderModel->with(['packages','services'])->find($id);
        $services = $order->services->pluck('id');
        return view('admin.orders.invoice',compact('order','services'));
    }

    public function export(Request $request)
    {
        return (new InvoicesExport)->download('payment-'.date('d-m-Y_H-m-s').'.xlsx');
    }

}
