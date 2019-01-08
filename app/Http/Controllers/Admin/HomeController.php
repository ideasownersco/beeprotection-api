<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Holiday;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @var Holiday
     */
    private $holidayModel;
    /**
     * @var Driver
     */
    private $driverModel;
    /**
     * @var User
     */
    private $userModel;
    /**
     * @var Order
     */
    private $orderModel;

    /**
     * Create a new controller instance.
     *
     * @param Holiday $holidayModel
     * @param Driver $driverModel
     * @param User $userModel
     * @param Order $orderModel
     */
    public function __construct(Holiday $holidayModel,Driver $driverModel, User $userModel, Order $orderModel)
    {
        $this->holidayModel = $holidayModel;
        $this->driverModel = $driverModel;
        $this->userModel = $userModel;
        $this->orderModel = $orderModel;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $today = Carbon::now();

        $dates = [];
        foreach (range(1, 20) as $i) {
            $dates[] = $today->addDays($i);
        }

        $activeDate = $request->date ? Carbon::parse($request->date)->format('d M') : Carbon::now()->format('d M');

        $queryDate = Carbon::parse($activeDate)->toDateString();

        /**
         * Remove after 1 Jan
         */
        $activeMonth = $request->date ? Carbon::parse($request->date)->format('M') : Carbon::now()->format('M');
        if($activeMonth !== 'Dec') {
            $queryDate = Carbon::parse($activeDate . ' 2019')->toDateString();
        }


        $driversCount = $this->driverModel->count();
        $customersCount = $this->userModel->customers()->count();
        $ordersCount = $this->orderModel->success()->count();
        $revenue = $this->orderModel->success()->sum('total');

        $drivers = $this->driverModel->has('user')->with(['user'])->get();

        $driverNames = $drivers->map(function($driver) {
            return "'".$driver->user->name."'";
        })->flatten()->toArray();

        $driverNames = '['.implode(', ',$driverNames).']';

        $recentOrders = $this->orderModel
            ->with(['job.driver.user','address.area','services','packages.category','user'])
            ->success()
            ->whereDate('date',$queryDate)
//            ->orderBy('appointment_time','ASC')
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->oldest()
            ->get()
        ;

        $completed = 0;
        $working = 0;
        $driving = 0;
        $pending = 0;


        foreach($recentOrders as $order) {
            switch ($order->job->status) {
                case 'completed':
                    $completed++;
                    break;
                case 'driving':
                    $driving++;
                    break;
                case 'pending':
                    $pending++;
                    break;
                case 'working':
                case 'reached':
                    $working++;
                    break;
            }

        }

        $events = $recentOrders->map(function($order) use ($activeDate) {

            $to = Carbon::parse($order->date . $order->calculateDuration());

            // to fix JS bug that breaks table if time crossed 12AM
            $toFormatted = $to->format('H');
            if(!($toFormatted > 1 && $toFormatted < 23)) {
                $to = Carbon::parse('23:59:00');
            }

            return [
                'id' => $order->id,
                'name' => $order->id . ': '.optional(optional($order->address)->area)->name,
                'driver' => optional(optional(optional($order->job)->driver)->user)->name,
                'from' => Carbon::parse($order->date . $order->time)->toDateTimeString(),
                'to' => $to->toDateTimeString(),
                'class' => optional($order->job)->button_name
            ];
        })->toJson();


        $dateRange = $this->generateDateRange(Carbon::now(),Carbon::now()->addDays(7));


        $holidays = $this->holidayModel->whereDate('date','>=',Carbon::today()->toDateString())->get();

        return view('admin.home',compact('driversCount','customersCount','ordersCount','revenue','recentOrders','driverNames','events','dateRange','activeDate','working','completed','driving','pending','holidays'));
    }

    public function saveHoliday(Request $request)
    {
        $this->validate($request,[
            'date' => 'required'
        ]);

        $date = $request->date;
        $parsedDate = Carbon::parse($date)->toDateString();
        $duplicate = $this->holidayModel->where('date',$parsedDate)->first();

        if($duplicate) {
            return redirect()->back()->with('success','Saved');
        }

        $this->holidayModel->create(['date'=>$parsedDate]);

        return redirect()->back()->with('success','Saved');

    }
    private function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];

        for($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('d M');
        }

        return $dates;
    }

    public function deleteHoliday($id)
    {
        $holiday = $this->holidayModel->find($id);
        if($holiday) {
            $holiday->delete();
        }
        return redirect()->back()->with('success','Deleted');
    }
}
