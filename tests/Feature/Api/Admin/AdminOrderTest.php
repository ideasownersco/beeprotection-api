<?php

namespace Tests\Feature\Driver;

use App\Models\Address;
use App\Models\Timing;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderTest extends TestCase
{

    use RefreshDatabase;


    public function test_admin_gets_working_orders()
    {
        $admin = $this->_createAdmin();

        $header = $this->_createHeader(['api_token' => $admin->api_token]);

        $validOrder1 = $this->_createOrder([
            'date' => Carbon::now()->addDays(1)->toDateString(),
        ]);

        $validOrder2 = $this->_createOrder([
            'date' => Carbon::now()->addDays(1)->toDateString(),
        ]);

        $expiredOrder = $this->_createOrder([
            'date' => Carbon::now()->subDays(1)->toDateString(),
        ]);

        $expiredOrder1 = $this->_createOrder([
            'date' => Carbon::now()->subDays(1)->toDateString(),
        ]);

        $startedAt = Carbon::now()->toDateTimeString();

        $validJob1 = $validOrder1->job()->create(['driver_id' => 22,'accepted'=>1,'status'=>'working','started_working_at' => $startedAt, 'stopped_working_at' => null]);
        $validJob2 = $validOrder2->job()->create(['driver_id' => 22,'accepted'=>1,'status'=>'working', 'started_working_at' => $startedAt,  'stopped_working_at' => null]);
        $confirmedJob = $expiredOrder1->job()->create(['driver_id' => 22 ,'accepted'=>1,'status'=>'confirmed']);

        $response = $this->json('GET', '/api/company/orders/working', [], $header);

        $response->assertJson(['success'=>true,'data'=>[['id'=>$validOrder1->id],['id' => $validOrder2->id]]]);

        $response->assertJsonMissing(['id'=>$confirmedJob->id,]);

    }

 public function test_admin_gets_upcoming_orders()
    {
        $admin = $this->_createAdmin();

        $header = $this->_createHeader(['api_token' => $admin->api_token]);

        $validOrder1 = $this->_createOrder([
            'date' => Carbon::now()->addDays(1)->toDateString(),
        ]);

        $validOrder2 = $this->_createOrder([
            'date' => Carbon::now()->addDays(1)->toDateString(),
        ]);

        $expiredOrder = $this->_createOrder([
            'date' => Carbon::now()->subDays(1)->toDateString(),
        ]);

        $expiredOrder1 = $this->_createOrder([
            'date' => Carbon::now()->subDays(1)->toDateString(),
        ]);

        $validJob1 = $validOrder1->job()->create(['driver_id' => 22,'accepted'=>1,'status'=>'pending']);
        $expiredJob = $expiredOrder->job()->create(['driver_id' => 22,'accepted'=>1,'status'=>'pending']);
        $confirmedJob = $expiredOrder1->job()->create(['driver_id' => 22 ,'accepted'=>1,'status'=>'confirmed']);

        $response = $this->json('GET', '/api/company/orders/upcoming', [], $header);

        $response->assertJson(['success'=>true,'data'=>[['id'=>$validJob1->id]]]);

        $response->assertJsonMissing(['id'=>$expiredJob->id,]);
        $response->assertJsonMissing(['id'=>$confirmedJob->id,]);

    }


    /**
     *
     * Get the current working order
     */
    public function test_admin_assigns_order_to_driver()
    {

        $customer = $this->_createCustomer();
        $header = $this->_createHeader(['api_token' => $customer->api_token]);
        $address = factory(Address::class)->create();
        $postData = [
            'date'    => '23-02-2018',
            'time'    => '22:00',
            'total' => 200,
            'address_id' => $address->id,
            'items'   => [
                [
                    'category' => 2,
                    'package'  => 3,
                    'services' => [10, 12],
                    'total' => 100
                ]
            ],
        ];

        $request = $this->json('POST', '/api/customer/orders', $postData, $header);

        $this->assertDatabaseHas('orders',[
            'user_id' => $customer->id,
            'address_id' => $address->id,
            'date' => '2018-02-23',
            'time' => '22:00:00'
        ]);

        $this->assertDatabaseHas('order_packages',[
            'package_id' => 3,
        ]);

        $this->assertDatabaseHas('order_services',[
            'service_id' => 10,
        ]);

        $this->assertDatabaseHas('order_services',[
            'service_id' => 12,
        ]);

    }


}
