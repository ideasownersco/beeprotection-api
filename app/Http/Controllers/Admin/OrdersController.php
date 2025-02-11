<?php

namespace App\Http\Controllers\Admin;

use App\Core\InvoicesExport;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Driver;
use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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
     * @var Area
     */
    private $areaModel;

    /**
     * OrdersController constructor.
     * @param Order $orderModel
     * @param Driver $driverModel
     * @param Area $areaModel
     */
    public function __construct(Order $orderModel,Driver $driverModel,Area $areaModel)
    {
        $this->orderModel = $orderModel;
        $this->driverModel = $driverModel;
        $this->areaModel = $areaModel;
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
            if($driver->id !== optional(optional($order->job)->driver)->id) {
                $driverNames->push(['name'=> $driver->user->name,'id'=>$driver->id]);
            }
        }

        $driverNames = $driverNames->pluck('name','id');

        $areas = $this->areaModel->with(['parent'])->withCount(['orders'])->where('parent_id','!=',null)->pluck('name_en','id');

        $todaysDate = Carbon::now()->format('Y-m-d');

        $items = collect();

        $services = $order->services;

        foreach ($order->packages as $package) {

            $orderServices = $services->where('package_id',$package->id)->pluck('id')->toArray();

            $items->put($package->id,['id'=>$package->id,'services' => $orderServices ]);

        }

        $items = $items->toJson();

        return view('admin.orders.view', compact('order','title','driverNames','areas','todaysDate','items'));
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

            if($order->job) {
                $order->job->assignDriver($driver->id,true);
            } else {
                $order->create(true,$driver);
            }

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

    public function updateCustomer($id,Request $request)
    {
        $this->validate($request,[
            'name'     => 'required|max:50',
            'email'    => 'email|sometimes',
            'mobile'   => 'required|digits:8',
        ]);

        $order = $this->orderModel->find($id);

        $order->update([
            'customer_name' => $request->name,
            'customer_email' => $request->email,
            'customer_mobile' => $request->mobile
        ]);

        return redirect()->back()->with(['success' => 'Updated']);

    }
    public function updateWashType($id,Request $request)
    {
        $this->validate($request,[
//            'name'     => 'required|max:50',
//            'email'    => 'email|sometimes',
//            'mobile'   => 'required|digits:8',
        ]);

        $order = $this->orderModel->find($id);

        return redirect()->back()->with(['success' => 'Updated']);

    }

    public function updateAmount($id,Request $request)
    {
        $this->validate($request, [
            'total'     => 'required|numeric',
        ]);

        $order = $this->orderModel->find($id);

        $order->total = $request->total;
        $order->save();

        return redirect()->back()->with(['success' => 'Updated']);

    }

    public function updateDateTime($id,Request $request)
    {
        $this->validate($request,[
            'date' => 'required|date',
            'time' => 'required'
        ]);

        $order = $this->orderModel->find($id);
        $order->date = $request->date;
        $order->time = $request->time;
        $order->save();

        return redirect()->back()->with(['success' => 'Updated']);

    }

    public function updateAddress($id,Request $request)
    {
        $this->validate($request,[
            'area_id'     => 'required',
            'block'    => 'required',
            'street'    => 'required',
            'latitude'    => 'required',
            'longitude'    => 'required',
        ]);

        $order = $this->orderModel->find($id);

        $address = $order->address;

        if($address) {
            $address = $address->update($request->all());
        } else {
            $address = $order->address()->create($request->all());
        }

        return redirect()->back()->with(['success' => 'Updated']);

    }

    public function updateJobStatus($id,Request $request)
    {
        $this->validate($request,[
            'status' => 'required|in:pending,driving,reached,working,completed,cancelled'
        ]);
        $order = $this->orderModel->find($id);

        $job = $order->job;
        $job->status = $request->status;
        $job->save();

        return redirect()->back()->with(['success' => 'Updated Job Status']);

    }

    public function getRevenue(Request $request)
    {

        if($request->month) {
            $month = Carbon::parse($request->month)->format('M-Y');
        } else {
            $month = Carbon::now()->format('M-Y');
        }

        $prevMonth = Carbon::parse($month)->subMonth(1)->format('M-Y');
        $nextMonth = Carbon::parse($month)->addMonth(1)->format('M-Y');

        $period = CarbonPeriod::create(Carbon::parse('first day of '.$month), Carbon::parse('last day of '.$month));

        $data = [];

        $orders = $this->orderModel
            ->whereHas('job',function($q) {
                $q->valid()
                ;
            })
            ->success()->whereMonth('date',Carbon::parse($month)->format('m'))->get(['date','total']);

        foreach ($period as $day) {
            $date = Carbon::parse($day)->format('Y-m-d');
            $total = $orders->where('date',$date)->sum('total');
            $data[] = ['title'=>$total.'KD','start' =>$date];
        }

        $payload = collect($data)->toJson();

        $goToDate = Carbon::parse($month)->format('Y-m-d');

        return view('admin.orders.revenue',compact('month','nextMonth','prevMonth','payload','goToDate'));
    }
}
