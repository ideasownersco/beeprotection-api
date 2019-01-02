<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['locale'])->namespace('Api')->group(function () {

    Route::post('push_token/register','UsersController@storePushToken');
    Route::post('device/uuid/register','UsersController@storeDeviceID');
    Route::post('freewash/check','UsersController@hasFreeWash');
    Route::post('freewash/set','UsersController@setFreeWash');

    /**
     * Auth Routes
     */
    Route::group(['auth', 'prefix' => 'auth'], function () {

        Route::get('logout', 'Auth\LoginController@logout');
        Route::post('login', 'Auth\LoginController@login');
        Route::post('register', 'Auth\LoginController@register');
        Route::post('password/forgot', 'Auth\LoginController@forgotPassword'); // send email
        Route::post('password/recover', 'Auth\LoginController@recoverPassword'); // send email
        Route::post('password/update', 'Auth\LoginController@updatePassword'); // send email
        Route::post('registration/confirm', 'Auth\LoginController@confirmRegistration'); // send email
        Route::post('registration/confirm/resend', 'Auth\LoginController@reSendConfirmRegistrationSMS'); // send email
    });

    /**
     * Routes that does not need authentication, or api middleware
     */
    Route::post('jobs/{id}/update/location','\App\Http\Controllers\Api\Driver\JobsController@updateLocation');

    Route::get('categories','CategoriesController@index')->name('category');

    Route::post('timings','TimingsController@index');

    Route::get('areas','AreasController@index');

    /**
     * API Authenticated Routes
     */
    Route::middleware(['auth:api'])->group(function () {

        Route::middleware(['customer'])->prefix('customer')->namespace('Customer')->group(function () {

            /**
             * GET Addresses
             */
            Route::get('addresses', 'AddressesController@index');

            /**
             * POST an Address
             */
            Route::post('addresses', 'AddressesController@store');
            Route::post('addresses/{id}/update', 'AddressesController@update');
            Route::post('addresses/delete', 'AddressesController@delete');

            /**
             * GET all Orders
             */
            Route::get('orders/upcoming', 'OrdersController@getUpcomingOrders');
            Route::get('orders/working', 'OrdersController@getWorkingOrder');
            Route::get('orders/past', 'OrdersController@getPastOrders');
            Route::get('orders/{id}/details', 'OrdersController@getDetail');

            /**
             * CREATE an Order
             */
            Route::post('checkout', 'CheckoutController@checkout');

            Route::get('cart/make','CartController@makeItems');

        });

        /**
         * Driver Routes
         */

        Route::middleware(['driver'])->prefix('driver')->namespace('Driver')->group(function () {

            Route::get('profile','ProfileController@getProfile');
            Route::post('profile/update','ProfileController@update');

            Route::get('orders/upcoming', 'OrdersController@getUpcomingOrders');
            Route::get('orders/working', 'OrdersController@getWorkingOrder');
            Route::get('orders/past', 'OrdersController@getPastOrders');
            Route::get('orders/{id}/details', 'OrdersController@getDetail');
            Route::post('orders/{id}/invoice/print','OrdersController@printInvoice');

            Route::get('jobs','JobsController@index');
            Route::get('jobs/{id}/photos','JobsController@getPhotos');
            Route::post('jobs/{id}/start/drive','JobsController@startDriving');
            Route::post('jobs/{id}/stop/drive','JobsController@stopDriving');
            Route::post('jobs/{id}/start/work','JobsController@startWorking');
            Route::post('jobs/{id}/stop/work','JobsController@stopWorking');
            Route::post('jobs/{id}/photos','JobsController@uploadPhotos');
            Route::post('jobs/{id}/photos/approve','JobsController@approvePhotos');
        });
        /**
         * Admin Specific API Routes
         */
        Route::middleware(['admin'])->prefix('company')->namespace('Admin')->group(function () {

            /**
             * Get Drivers
             */
            Route::get('drivers', 'DriversController@getCompanyDrivers');
            Route::get('drivers/{id}/details', 'DriversController@getDetails');
            Route::post('drivers/update', 'DriversController@updateDriver');

            /**
             * Assign Driver for Order
             */
            Route::post('orders/{id}/drivers/assign', 'DriversController@assignOrderToDriver');
            Route::get('orders/{id}/details', 'OrdersController@getDetail');

            /**
             * GET all Orders
             */
            Route::get('orders', 'OrdersController@index');

            Route::get('orders/upcoming', 'OrdersController@getUpcomingOrders');
            Route::get('orders/working', 'OrdersController@getWorkingOrders');
            Route::get('orders/past', 'OrdersController@getPastOrders');

            Route::get('timings','TimingsController@index');

        });
    });


});

Route::fallback(function(){
    return response()->json(['success'=>false,'message' => 'Route Missing']);
});
