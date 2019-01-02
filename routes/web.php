<?php

use App\Events\UserActivated;
use App\Events\UserActivatedSMS;
use App\Models\Area;
use App\Models\Order;
use App\Models\PushToken;
use App\Models\User;
use Berkayk\OneSignal\OneSignalFacade;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use IZaL\Knet\KnetBilling;
use Twilio\Rest\Client;
use App\Core\GoogleMapsGeocoder;

Route::get('sss',function(){

    $photos = \App\Models\JobPhoto::all();

    foreach ($photos as $photo) {
        $name = parse_url($photo->url);
        if(file_exists(base_path($name['path']))) {
            unlink($name['path']);
        }
    }
    dd('s');

});

Route::get('nacount',function(){
    $users = User::where('active',0)->get();
    dd($users->count());
});

Route::get('nasms-count',function(){
    $time = Carbon::now()->subMinutes(5)->toDateTimeString();

    $users = User::where('active',0)
        ->orWhereNotNull('registration_code')
        ->whereDate('created_at','<',$time)
        ->get(['mobile','id','email'])
    ;
    foreach ($users as $user) {
        echo $user->id . ' : ' . $user->mobile . ' : ' . $user->email;
        echo "<br>";
    }
//    dd($users->pluck());
});

Route::get('nasms',function(){

    $time = Carbon::now()->subMinutes(5)->toDateTimeString();
    $users = User::where('active',0)
        ->orWhereNotNull('registration_code')
        ->whereDate('created_at','<',$time)
        ->take(50)
        ->get()
    ;

    foreach ($users as $user) {

        $user->registration_code = null;
        $user->save();

        if(app()->env === 'production') {
            try {
                event(new UserActivatedSMS($user));
            } catch (\Exception $e) {
                $user->active = 0;
                $user->save();
                $message = $user->id .' : '. $e->getMessage();
//                Log::info($message);
            }
        }
    }

    dd($users->count());
});

Route::get('activate_users',function(){

    $time = Carbon::now()->subMinutes(10)->toDateTimeString();

    $users = User::
        where('active',0)
        ->where('created_at','<',$time)
        ->take(10)
        ->get()
    ;


    foreach ($users as $user) {
        $password = rand(10000000,99999999);
        $user->active = 1;
        $user->password = bcrypt($password);
        $user->save();

        if(app()->env === 'production') {
            try {
                event(new UserActivated($user,$password));
            } catch (\Exception $e) {
                $user->active = 0;
                $user->save();
                $message = $user->id .' : '. $e->getMessage();
//                Log::info($message);
            }
        }
    }

    $inactiveUsers = User::where('active',0)
        ->whereDate('created_at','<',$time)
        ->take(10)
        ->count()
    ;

    dd($inactiveUsers);

});

Route::get('tz',function(){

    $date = Carbon::now('America/Mexico_City')->toDateString();

    dd($date);



});
Route::get('latlng',function() {

    $areas = Area::where('latitude',null)->get();

//    foreach ($areas as $area) {
//
//        $response = \GoogleMaps::load('geocoding')
//            ->setParam ([
//                'address' => $area->name . ' Kuwait',
//            ])
//            ->get();
//
//        $collection = collect(json_decode($response))->flatten();
//
//        $location = $collection->pluck('geometry')->flatten()->pluck('location');
//
//        $area->latitude = $location[0]->lat;
//        $area->longitude = $location[0]->lng;
//        $area->save();
//    }

});

Route::get('areacount',function(){
    $areas = Area::withCount(['orders'=>function($q){
        $q->success();
    }])->orderBy('orders_count','DESC')->paginate(10);

    foreach ($areas as $area) {
        echo nl2br($area->name . ' : ' . $area->orders_count) ."<br>";
    }
});

Route::get('usercount',function(){

    $users = User::with(['orders'=>function($q){
        $q->where('free_wash',1);
    }])->withCount(['orders'=>function($q){
        $q->success();
    }])->orderBy('orders_count','DESC')->paginate(10);

    foreach ($users as $user) {
        echo nl2br($user->id . ' : ' . $user->orders_count) ."<br>";
    }
//    dd($users->toArray());
});
Route::get('appointment_time',function() {

    $orders = Order::all();
    foreach ($orders as $order) {
        $order->appointment_time = \Carbon\Carbon::parse($order->date . ' ' . $order->time)->toDateTimeString();
        $order->save();
    }
});

Route::get('purge',function() {
    // delete all invalid orders
    $date = \Carbon\Carbon::yesterday()->toDateString();
    $orders = Order::where('status','!=','success')->whereDate('date','<',$date)->get();

    foreach ($orders as $order) {

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
    }

    dd($orders->count());

});

Route::get('date',function() {

    $date = \Carbon\Carbon::now()->toDateString();

    $driver = \App\Models\Driver::first();


    $user = User::find(15);

    $order = $user->orders()->create([
        'user_id' =>  15,
        'invoice' => rand(2,5),
        'address_id' => 25,
        'total' => 1,
        'date' => '2018-07-25',
        'time' => '16:00:00',
        'status' => 'success',
        'free_wash' => 1,
    ]);

    dd($order->create());

//    $driver->incrementJobCount($date);

});

Route::get('f',function() {

    $freeWashesArray = \App\Models\FreeWash::where('has_free_wash',1)->where('user_id','!=',null)->pluck('user_id');
    $freeWashes = \App\Models\FreeWash::where('has_free_wash',1)->where('user_id','!=',null)->get();

    $orders = Order::whereIn('user_id',$freeWashesArray)
        ->where('status','success')
        ->where('free_wash',1)
        ->pluck('user_id')
        ->toArray();

    foreach ($freeWashes as $freeWash) {
        if(in_array($freeWash->user_id,$orders)) {
            $freeWash->has_free_wash = 0;
            $freeWash->save();
        }
    }

    $freeWashesArray = \App\Models\FreeWash::where('has_free_wash',1)->where('user_id','!=',null)->pluck('user_id');
    dd($freeWashesArray);

});

Route::get('sms',function() {
    $sid = env('TWILIO_USERNAME');
    $token = env('TWILIO_PASSWORD');
    $fromPhone = env('TWILIO_PHONE_NUMBER');

    $toPhone = '+96597973464';

    $messageBody = "BeeProtection : Your Registration code is 1234";

    try {
        $client = new Client($sid, $token);

        $client->messages->create(
            $toPhone,
            [
                'from' => $fromPhone,
                'body' => $messageBody,
            ]
        );
    } catch (\Exception $e) {
        dd($e->getMessage());
    }


});

Route::get('pn',function() {
//    $oneSignal = new Berkayk\OneSignal\OneSignalClient(env('ONE_SIGNAL_APP_ID'),env('ONE_SIGNAL_REST_KEY'),null);
//    $player =   $oneSignal->createPlayer(['device_type' => 0,'identifier' => '5aa132edf676c07c055b2f35c8440f7e7ba0f269214155e45268f594c46cc696']);
//    return $player;
//    dd($player);

//    $pushToken = \App\Models\PushToken::where('token','5aa132edf676c07c055b2f35c8440f7e7ba0f269214155e45268f594c46cc696')->first();

//    try {
//        $player = OneSignalFacade::createPlayer(['device_type' => 0,'identifier' => '5aa132edf676c07c055b2f35c8440f7e7ba0f269214155e45268f594c46cc696']);
//
//        $res = json_decode($player->getBody(),true);
//
//        if(is_array($res)) {
//            if(isset($res['id'])) {
//                $pushToken->push_id = $res['id'];
//                $pushToken->save();
//            }
//        }
//
//    } catch (\Exception $e) {
//        dd($e->getMessage());
//    }


//    $customerToken = ['5aa132edf676c07c055b2f35c8440f7e7ba0f269214155e45268f594c46cc696'];
//    $customerToken = ['e86bf5bb-c2f3-4911-8147-841f0c82b612'];

//    $admins = User::where('admin',1)->pluck('id')->toArray();
//    $adminPushTokens = PushToken::whereIn('user_id',$admins)->pluck('push_id')->toArray();

//    dd($adminPushTokens);

//    $pushTokens = array_unique(array_filter(array_merge($customerToken,[])));
//
//    $jobAddress = 'Salwa';
//    $customerMessage = 'Driver on his way to process order at ' . $jobAddress .' . Tracking is Available now';
//
//    OneSignalFacade::sendNotificationToUser($customerMessage,$pushTokens);

//    $order = Order::find(133);
//
//    $driverToken = $order->job->driver->user->push_tokens->pluck('push_id')->toArray();
//
//
//    $admins = User::where('admin',1)->pluck('id')->toArray();
//    $adminPushTokens = PushToken::whereIn('user_id',$admins)->pluck('push_id')->toArray();
//
//    $pushTokens = array_values(array_unique(array_filter(array_merge($driverToken,$adminPushTokens))));
//
//    $message = 'Order #'.$order->id . '. Order placed on '.$order->scheduled_time . ' at '.$order->address->area->name;
//
//    OneSignalFacade::sendNotificationToUser(
//        $message,$pushTokens,null,['order_id'=>$order->id,'type'=>'order.created']
//    );
});

Auth::routes();

Route::get('knet-test',function() {
    $order = Order::first();
//    return view('payment.failure',compact('order'));
    return view('payment.success',compact('order'));
});

Route::post('knet-pay',function () {
    $order = \App\Models\Order::first();
    $successURL = route('payment.knet.response.success');
    $errorURL = route('payment.knet.error');
    $knetAlias = env('KNET_ALIAS');
    try {
        $knetGateway = new KnetBilling([
            'alias'        => $knetAlias,
            'resourcePath' => base_path() . '/'
        ]);
        $knetGateway->setResponseURL($successURL);
        $knetGateway->setErrorURL($errorURL);
        $knetGateway->setAmt($order->total);
        $knetGateway->setTrackId($order->id);
        $knetGateway->requestPayment();
        $paymentURL = $knetGateway->getPaymentURL();
        $order->payment_id = $knetGateway->getPaymentID();
        $order->status = 'checkout';
        $order->save();
        return redirect()->away($paymentURL);
    } catch (\Exception $e) {
        $order->status = 'error';
        $order->save();
        return response()->json(['success'=>false,'error', 'حدث خلل اثناء التحويل الي موقع الدفع']);
    }
});

Route::get('test', 'HomeController@test');
Route::get('/', 'HomeController@index');
Route::get('logout',function(){
    Auth::logout();
    return redirect()->route('login');
});

Horizon::auth(function ($request) {
    $user = $request->user();
    if($user->admin) {
        return true;
    }

    return false;
});

Route::get('hack','HomeController@hack');

Route::get('home', 'HomeController@index')->name('home');

Route::post('payment/knet/{orderID}/checkout','CheckoutController@makeKnetPayment')->name('checkout.knet');
//Route::post('payment/knet/checkout','CheckoutController@makeKnetPayment')->name('checkout.knet');

Route::get('payment/knet/success','CheckoutController@onKnetPaymentSuccess')->name('payment.knet.success');
Route::get('payment/knet/error','CheckoutController@onKnetPaymentError')->name('payment.knet.error');
Route::post('payment/knet/response/success','CheckoutController@onKnetPaymentResponseSuccess')->name('payment.knet.response.success');
Route::get('order/{id}/success','CheckoutController@onOrderSuccess')->name('order.success');

Route::get('orders/{id}/invoice','HomeController@getInvoice');
Route::post('orders/invoice/print','HomeController@printInvoice');

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'admin']], function () {
    Route::resource('categories','CategoriesController');
    Route::resource('packages','PackagesController');
    Route::resource('services','ServicesController');
    Route::resource('drivers','DriversController');
    Route::resource('orders','OrdersController');
    Route::resource('timings','TimingsController');
    Route::resource('users','UsersController');
    Route::resource('areas','AreasController');
    Route::resource('notifications','NotificationsController');
    Route::post('holiday','HomeController@saveHoliday')->name('holiday');
    Route::post('working_hours','HomeController@saveHoliday')->name('working_hours');
    Route::post('orders/{id}/driver/assign','OrdersController@assignDriver')->name('orders.driver.assign');
    Route::post('categories/reorganize','CategoriesController@reOrganize')->name('categories.reorganize');
    Route::post('packages/reorganize','PackagesController@reOrganize')->name('packages.reorganize');

    Route::get('orders/{id}/invoice','OrdersController@getInvoice')->name('invoice');
    Route::get('orders/export/csv','OrdersController@export');
    Route::get('holidays/{id}/delete','HomeController@deleteHoliday')->name('holiday.delete');
    Route::post('drivers/holiday/assign','DriversController@assignHoliday')->name('drivers.holiday.assign');
    Route::get('drivers/holidays/{id}/delete','DriversController@deleteHoliday')->name('drivers.holiday.delete');

    Route::get('/','HomeController@index')->name('home');
});
