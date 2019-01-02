<?php

namespace Tests\Feature\Admin;

use App\Exceptions\DriverIsBusyException;
use App\Http\Resources\DriverResource;
use App\Models\Driver;
use App\Models\Job;
use App\Models\Location;
use App\Models\Order;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminOrdersTest extends TestCase
{

    use RefreshDatabase;

    public function test_admin_assigns_driver_for_order()
    {

        $oldDriver = $this->_createDriver();


        $user = $this->_createUser(['admin' => 1]);
        $header = $this->_createHeader(['api_token' => $user->api_token]);

        $order = factory(Order::class)->create();

        $order->create();

        $this->assertDatabaseHas('jobs', ['order_id' => $order->id,'driver_id'=>$oldDriver->id]);
        $this->assertDatabaseHas('blocked_dates', ['driver_id' => $oldDriver->id]);

        $newDriver = $this->_createDriver();

        $response = $this->json('POST', '/api/company/orders/' . $order->id . '/drivers/assign', ['driver_id' => $newDriver->id], $header);

        $this->assertDatabaseHas('orders', ['id' => $order->id]);
        $this->assertDatabaseHas('jobs', ['order_id' => $order->id,'driver_id'=>$newDriver->id]);
        $this->assertDatabaseHas('blocked_dates', ['order_id' => $order->id,'driver_id'=>$newDriver->id]);
        $this->assertDatabaseMissing('blocked_dates', ['order_id' => $order->id,'driver_id'=>$oldDriver->id]);

//        $response->assertJson([
//            'success' => true,
//            'data'    => ['id' => $order->id, 'status' => 'assigned', 'driver_id' => $driver1->id]
//        ]);

    }


    public function test_admin_cannot_assigns_driver_for_order_if_driver_is_already_assigned_for_another_job()
    {
        $driver1 = factory(Driver::class)->create();

        $user = $this->_createUser(['admin' => 1]);
        $header = $this->_createHeader(['api_token' => $user->api_token]);

        $order = factory(Order::class)->create();
        $job = factory(Job::class)->states('assigned')->create(['driver_id' => $driver1->id]);

        $response = $this->json('POST', '/api/company/orders/' . $order->id . '/driver/assign', ['driver_id' => $driver1->id], $header);

//        $response->assertJson([
//            'success' => false,
//            'type'    => 'driver_busy'
//        ]);

        $this->assertDatabaseHas('jobs', ['driver_id' => $driver1->id, 'order_id' => $order->id]);
        $this->assertDatabaseHas('cancelled_jobs', ['driver_id' => $driver1->id, 'order_id' => $order->id]);

    }

    public function test_admin_cannot_assigns_driver_for_order_if_driver_is_already_working_on_another_job()
    {
        $driver1 = factory(Driver::class)->create();

        $user = $this->_createUser(['admin' => 1]);
        $header = $this->_createHeader(['api_token' => $user->api_token]);

        $order = factory(Order::class)->create();
        $job = factory(Job::class)->states('working')->create(['driver_id' => $driver1->id]);

        $response = $this->json('POST', '/api/company/orders/' . $order->id . '/driver/assign', ['driver_id' => $driver1->id], $header);

        $response->assertJson([
            'success' => false,
            'type'    => 'driver_busy'
        ]);

        $this->assertDatabaseHas('jobs', ['driver_id' => $driver1->id, 'order_id' => $order->id]);

    }

    public function test_admin_gets_available_drivers_for_job()
    {
        $driver1 = factory(Driver::class)->states('available')->create();
        $driver2 = factory(Driver::class)->states('offline')->create();
        $driver3 = factory(Driver::class)->states('available')->create();

        $user = $this->_createUser(['admin' => 1]);
        $header = $this->_createHeader(['api_token' => $user->api_token]);

        $response = $this->json('GET', '/api/company/drivers', ['status' => 'available'], $header);

        $response->assertJson([
            'data' => [['id' => $driver1->id], ['id' => $driver3->id]]
        ]);

    }

}
