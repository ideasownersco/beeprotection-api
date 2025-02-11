<?php

use App\Events\UserActivated;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use IZaL\Knet\KnetBilling;

//Route::get('activate_users',function(){
//
//    $time = Carbon::now()->subMinutes(10)->toDateTimeString();
//
//    $users = User::
//    where('active',0)
//        ->where('created_at','<',$time)
//        ->take(10)
//        ->get()
//    ;
//
//
//    foreach ($users as $user) {
//        $password = rand(10000000,99999999);
//        $user->active = 1;
//        $user->password = bcrypt($password);
//        $user->save();
//
//        if(app()->env === 'production') {
//            try {
//                event(new UserActivated($user,$password));
//            } catch (\Exception $e) {
//                $user->active = 0;
//                $user->save();
//            }
//        }
//    }
//
//    $inactiveUsers = User::where('active',0)
//        ->whereDate('created_at','<',$time)
//        ->take(10)
//        ->count()
//    ;
//
//    dd($inactiveUsers);
//
//});

//Route::get('latlng',function() {
//
//    $areas = Area::where('latitude',null)->get();

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
//
//});


//Route::post('knet-pay',function () {
//    $order = \App\Models\Order::first();
//    $successURL = route('payment.knet.response.success');
//    $errorURL = route('payment.knet.error');
//    $knetAlias = env('KNET_ALIAS');
//    try {
//        $knetGateway = new KnetBilling([
//            'alias'        => $knetAlias,
//            'resourcePath' => base_path() . '/'
//        ]);
//        $knetGateway->setResponseURL($successURL);
//        $knetGateway->setErrorURL($errorURL);
//        $knetGateway->setAmt($order->total);
//        $knetGateway->setTrackId($order->id);
//        $knetGateway->requestPayment();
//        $paymentURL = $knetGateway->getPaymentURL();
//        $order->payment_id = $knetGateway->getPaymentID();
//        $order->save();
//        return redirect()->away($paymentURL);
//    } catch (\Exception $e) {
//        return response()->json(['success'=>false,'error', 'حدث خلل اثناء التحويل الي موقع الدفع']);
//    }
//});

Auth::routes();

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
Route::get('payment/knet/success','CheckoutController@onKnetPaymentSuccess')->name('payment.knet.success');
Route::get('payment/knet/error','CheckoutController@onKnetPaymentError')->name('payment.knet.error');
Route::post('payment/knet/response/success','CheckoutController@onKnetPaymentResponseSuccess')->name('payment.knet.response.success');
Route::get('order/{id}/success','CheckoutController@onOrderSuccess')->name('order.success');

Route::get('orders/{id}/invoice','HomeController@getInvoice');
Route::post('orders/invoice/print','HomeController@printInvoice');

Route::get('/', 'HomeController@index');

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


    //Edit Order
    Route::post('orders/{id}/customer/update','OrdersController@updateCustomer')->name('orders.customer.update');
    Route::post('orders/{id}/type/update','OrdersController@updateWashType')->name('orders.type.update');
    Route::post('orders/{id}/amount/update','OrdersController@updateAmount')->name('orders.amount.update');
    Route::post('orders/{id}/datetime/update','OrdersController@updateDateTime')->name('orders.datetime.update');
    Route::post('orders/{id}/address/update','OrdersController@updateAddress')->name('orders.address.update');
    Route::post('orders/{id}/job/status/update','OrdersController@updateJobStatus')->name('orders.job.status.update');

    Route::get('revenue','OrdersController@getRevenue')->name('revenue.index');

    Route::get('/','HomeController@index')->name('home');
});

