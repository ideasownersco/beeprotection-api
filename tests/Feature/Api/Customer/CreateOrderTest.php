<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Area;
use App\Models\BlockedDate;
use App\Models\Category;
use App\Models\Order;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateOrderTest extends TestCase
{

    use RefreshDatabase;

    public function test_user_can_create_successful_cash_order()
    {
        $user = $this->_createCustomer();
//        $user->push_tokens()->create(['token'=>'f758b696281180f131c31389dc19eecd32f88724dc0d1cf1aae0693c731c0da1']);

        $area = factory(Area::class)->create();
        $address = factory(Address::class)->create(['area_id'=>$area->id]);

        $header = $this->_createHeader(['api_token' => $user->api_token]);

        $orderDate = Carbon::today()->toDateString();
        $timing = $this->_createTiming('11:00:00');
        $orderTime = $timing->id;

        $category = factory(Category::class)->create();
        $package = factory(Package::class)->create(['category_id' => $category->id]);
        $service1 = factory(Service::class)->create(['package_id' => $package->id]);
        $service2 = factory(Service::class)->create(['package_id' => $package->id]);

        $driver = $this->_createDriver();
//        $driver->user->push_tokens()->create(['token'=>'f758b696281180f131c31389dc19eecd32f88724dc0d1cf1aae0693c731c0da1']);

        $orderData = collect([
            'date'         => $orderDate,
            'time'         => $orderTime,
            'address_id'   => $address->id,
            'items'        => [
                [
                    'category' => $category->id,
                    'package'  => $package->id,
                    'services' => [$service1->id, $service2->id],
                    'total'    => 100
                ]
            ],
            'payment_mode' => 'cash',
            'user_id'      => 1
        ]);

        $orderTableData = $orderData->only(['user_id', 'date'])->toArray();
        $orderTableData['time'] = Carbon::parse($timing->name_en)->toTimeString();

        $response = $this->json('POST', '/api/customer/checkout', $orderData->toArray(), $header);

        $createdOrder = Order::where(['user_id' => $user->id,'date' =>$orderDate])->first();

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $createdOrder->id,
                    'status' => 'Success'
                ]
            ]);
        $this->assertDatabaseHas('orders', $orderTableData);
        $this->assertDatabaseHas('order_services', ['id'=>$service1->id]);
        $this->assertDatabaseHas('order_services', ['id'=>$service2->id]);

        $this->assertDatabaseHas('jobs', ['id'=>$createdOrder->job->id,'driver_id'=>$driver->id]);

    }

    public function test_user_can_create_pending_knet_order()
    {
        $user = $this->_createCustomer();
        $header = $this->_createHeader(['api_token' => $user->api_token]);

        $address = factory(Address::class)->create();

        $orderDate = Carbon::now()->addDay(rand(1, 1000))->toDateString();

        $timing = $this->_createTiming(Carbon::now()->addHours(rand(1, 5))->format('H:m:i'));
        $orderTime = $timing->id;

        $category = factory(Category::class)->create();
        $package = factory(Package::class)->create(['category_id' => $category->id]);
        $service1 = factory(Service::class)->create(['package_id' => $package->id]);
        $service2 = factory(Service::class)->create(['package_id' => $package->id]);

        $orderData = collect([
            'date'         => $orderDate,
            'time'         => $orderTime,
            'address_id'   => $address->id,
            'items'        => [
                [
                    'category' => $category->id,
                    'package'  => $package->id,
                    'services' => [$service1->id, $service2->id],
                    'total'    => 100
                ]
            ],
            'payment_mode' => 'knet',
            'user_id'      => 1
        ]);

        $orderTableData = $orderData->only(['user_id', 'date'])->toArray();
        $orderTableData['time'] = Carbon::parse($timing->name_en)->toTimeString();

        $response = $this->json('POST', '/api/customer/checkout', $orderData->toArray(), $header);

        $createdOrder = Order::where(['user_id' => $user->id,'date' =>$orderDate])->first();

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $createdOrder->id,
                    'status' => 'Checkout'
                ]
            ]);

        $this->assertDatabaseHas('orders', $orderTableData);
        $this->assertDatabaseHas('order_services', ['id'=>$service1->id]);
        $this->assertDatabaseHas('order_services', ['id'=>$service2->id]);

        $this->assertNull($createdOrder->job);

        $this->assertDatabaseMissing('jobs',['id' => 1]);
    }

    public function test_duration_for_quantity_packages()
    {
        $user = $this->_createCustomer();
//        $user->push_tokens()->create(['token'=>'f758b696281180f131c31389dc19eecd32f88724dc0d1cf1aae0693c731c0da1']);

        $area = factory(Area::class)->create();
        $address = factory(Address::class)->create(['area_id'=>$area->id]);

        $header = $this->_createHeader(['api_token' => $user->api_token]);

        $orderDate = Carbon::today()->toDateString();
        $timing = $this->_createTiming('11:00:00');
        $orderTime = $timing->id;

        $category = factory(Category::class)->create();
        $package = factory(Package::class)->create(['category_id' => $category->id,'show_quantity' => 1,'duration' =>60]);
        $package2 = factory(Package::class)->create(['category_id' => $category->id,'show_quantity' => 1,'duration' =>30]);
        $package3 = factory(Package::class)->create(['category_id' => $category->id,'show_quantity' => 1,'duration' =>30]);

        $driver = $this->_createDriver();
//        $driver->user->push_tokens()->create(['token'=>'f758b696281180f131c31389dc19eecd32f88724dc0d1cf1aae0693c731c0da1']);

        $orderData = collect([
            'date'         => $orderDate,
            'time'         => $orderTime,
            'address_id'   => $address->id,
            'items'        => [
                [
                    'category' => $category->id,
                    'package'  => $package->id,
                    'total'    => 100,
                    'quantity' => 22
                ],
                [
                    'category' => $category->id,
                    'package'  => $package2->id,
                    'total'    => 100,
                ],
                [
                    'category' => $category->id,
                    'package'  => $package3->id,
                    'total'    => 100,
                    'quantity' => 13
                ]
            ],
            'payment_mode' => 'cash',
            'user_id'      => 1
        ]);

        $orderTableData = $orderData->only(['user_id', 'date'])->toArray();
        $orderTableData['time'] = Carbon::parse($timing->name_en)->toTimeString();

        $response = $this->json('POST', '/api/customer/checkout', $orderData->toArray(), $header);

        $createdOrder = Order::where(['user_id' => $user->id,'date' =>$orderDate])->first();

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $createdOrder->id,
                    'status' => 'Success'
                ]
            ]);
        $this->assertDatabaseHas('orders', $orderTableData);
        $this->assertDatabaseHas('blocked_dates', ['from' => '11:01:00','to' => '15:59:00']);

        $this->assertDatabaseHas('jobs', ['id'=>$createdOrder->job->id,'driver_id'=>$driver->id]);

    }
//    public function test_customer_can_cancel_order()
//    {
//
//        $customer = $this->_createCustomer();
//        $header = $this->_createHeader(['api_token' => $customer->api_token]);
//
//        $address = factory(Address::class)->create();
//
//        $orderPayload = $this->_createOrderPostData(['date'=>'23-02-2018','time'=>'22:00','address_id'=>$address->id]);
//
//        $request = $this->json('POST', '/api/customer/checkout', $orderPayload, $header);
//
//        $this->assertDatabaseHas('orders',[
//            'user_id' => $customer->id,
//            'address_id' => $address->id,
//            'date' => '2018-02-23',
//            'time' => '22:00:00'
//        ]);
//
//
//        $this->assertDatabaseHas('order_packages',[
//            'package_id' => 1,
//        ]);
//
//        $this->assertDatabaseHas('order_services',[
//            'service_id' => 1,
//        ]);
//
//        $this->assertDatabaseHas('order_services',[
//            'service_id' => 2,
//        ]);
//
//    }


}
