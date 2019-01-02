<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedDate;
use App\Models\Category;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriversController extends Controller
{
    /**
     * @var Driver
     */
    private $driverModel;
    /**
     * @var User
     */
    private $userModel;
    /**
     * @var BlockedDate
     */
    private $blockedDateModel;
    /**
     * @var Order
     */
    private $orderModel;

    /**
     * DriversController constructor.
     * @param Driver $driverModel
     * @param User $userModel
     * @param BlockedDate $blockedDateModel
     * @param Order $orderModel
     */
    public function __construct(Driver $driverModel,User $userModel,BlockedDate $blockedDateModel,Order $orderModel)
    {
        $this->driverModel = $driverModel;
        $this->userModel = $userModel;
        $this->blockedDateModel = $blockedDateModel;
        $this->orderModel = $orderModel;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $drivers = $this->driverModel->all();
        $activeDrivers = $this->driverModel->has('user')->with(['user'])->active()->get();
        $driverNames = collect();

        foreach ($activeDrivers as $driver) {
            $driverNames->push(['name'=> $driver->user->name,'id'=>$driver->id]);
        }

        $driverNames = $driverNames->pluck('name','id');
        
        $driverHolidays = $this->blockedDateModel->has('driver.user')->with(['driver.user'])
            ->whereDate('date','>',Carbon::today()->toDateString())
            ->where('order_id',1)->get();

        $title = 'Drivers';
        return view('admin.drivers.index', compact('drivers','title','driverNames','driverHolidays'));
    }

    public function show($id)
    {
        $driver = $this->driverModel->find($id);

        $orders = $this->orderModel
            ->with(['job.driver.user','address.area','services','packages.category','user'])
            ->whereHas('job',function($q) use ($driver) {
                $q->where('driver_id',$driver->id);
            })
            ->success()
            ->paginate(10)
        ;

        $todaysCount = $this->orderModel->whereHas('job',function($q) use ($driver) {
            return $q->where('driver_id',$driver->id);
        })->success()->today()->count();

        $monthsCount = $this->orderModel->whereHas('job',function($q) use ($driver) {
            $q->where('driver_id',$driver->id);
        })->success()->month()->count();

        $yearsCount = $this->orderModel->whereHas('job',function($q) use ($driver) {
            $q->where('driver_id',$driver->id);
        })->success()->year()->count();

        $title = $driver->name;
        return view('admin.drivers.view',compact('title','driver','todaysCount','monthsCount','yearsCount','orders'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'     => 'required|max:50',
            'email'    => 'email|required|unique:users,email',
            'password' => 'required|confirmed',
            'mobile'   => 'required|unique:users,mobile',
            'offline' => 'boolean'
        ]);

        $name = $request->name;
        $email = strtolower($request->email);
        $password = bcrypt($request->password);
        $mobile = $request->mobile;
        $apiToken = str_random(16);

        $user = $this->userModel->create([
            'name'      => $name,
            'email'     => $email,
            'password'  => $password,
            'mobile'    => $mobile,
            'api_token' => $apiToken
        ]);

        $user->driver()->create([
            'offline' => $request->offline === !$request->offline,
            'start_time' => Carbon::parse($request->start_time)->toTimeString(),
            'end_time' => Carbon::parse($request->end_time)->toTimeString(),
        ]);

        if($request->hasFile('image')) {
            try {
                $image = $this->uploadImage($request->image);
                $user->image = $image;
                $user->save();
            } catch (\Exception $e) {
                redirect()->back()->with('success','The Image failed to Upload');
            }
        }
        return redirect()->back()->with('success','Driver Saved');
    }

    public function update(Request $request, $id)
    {
        $driver = $this->driverModel->find($id);

        $userID = $driver->user_id;

        $this->validate($request,[
            'name'     => 'nullable|max:50',
            'mobile'   => 'nullable|unique:users,mobile,'.$userID,
            'email'    => 'email|nullable|unique:users,email,'.$userID,
            'password' => 'nullable|confirmed',
        ]);

        $driver->offline = $request->offline;
        $driver->start_time =  Carbon::parse($request->start_time)->toTimeString();
        $driver->end_time =  Carbon::parse($request->end_time)->toTimeString();
        $driver->save();

        $user = $driver->user;

        if($request->filled('name')) {
            $user->name = $request->name;
        }

        if($request->filled('mobile')) {
            $user->mobile = $request->mobile;
        }

        if($request->filled('email')) {
            $user->email = $request->email;
        }

        if($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        if($request->hasFile('image')) {
            try {
                $image = $this->uploadImage($request->image);
                $user->image = $image;
                $user->save();
            } catch (\Exception $e) {
                $driver->delete();
                redirect()->back()->with('success','Drivers Could not be saved because The Image failed to Upload');
            }
        }

        return redirect()->route('admin.drivers.index')->with('success','Driver Updated');
    }

    public function destroy($id)
    {
        $driver = $this->driverModel->find($id);
        $driver->user()->delete();
        $driver->delete();
        return redirect()->back()->with('success','Driver Deleted');
    }

    public function assignHoliday(Request $request)
    {

        $this->validate($request,[
            'driver_id'     => 'required',
            'date' => 'required|date',
            'from' => 'required',
            'to' => 'required',
        ]);

        $driver = $this->driverModel->find($request->driver_id);

        $date = Carbon::parse($request->date)->toDateString();

        $params = [];
        $params['order_id'] = 1;
        $params['date'] = $date;

        $driver->blocked_dates()->create(array_merge($request->except('date'),$params));

        return redirect()->back()->with('success','Saved');

    }

    public function deleteHoliday($id)
    {
        $holiday = $this->blockedDateModel->find($id);

        $holiday->delete();

        return redirect()->back()->with('success','Deleted');

    }
}
