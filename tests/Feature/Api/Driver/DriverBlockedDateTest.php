<?php

namespace Tests\Feature\Driver;

use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\Package;
use App\Models\Service;
use App\Models\Timing;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DriverBlockedDateTest extends TestCase
{

    use RefreshDatabase;

    /**
     *
     * Get the current working job
     */
    public function test_driver_gets_blocked_on_order_date_time_test()
    {
        $user = $this->_createCustomer();
        $header = $this->_createHeader(['api_token' => $user->api_token]);

        $address = factory(Address::class)->create();

        $orderDate = Carbon::now()->addDays(1)->toDateString();
        $time = '10:00:00';
        $orderTime = $this->_createTiming($time);
        $transitTime = 30;

        $category = factory(Category::class)->create();
        $package1 = factory(Package::class)->create(['category_id' => $category->id, 'duration' => 20]);
        $package2 = factory(Package::class)->create(['category_id' => $category->id, 'duration' => 30]);
        $service1 = factory(Service::class)->create(['package_id' => $package1->id, 'duration' => 30]);
        $service2 = factory(Service::class)->create(['package_id' => $package1->id, 'duration' => 30]);
        $service3 = factory(Service::class)->create(['package_id' => $package2->id, 'duration' => 20]);

        $total = $package1->duration + $package2->duration + $service1->duration + $service2->duration + $service3->duration + $transitTime;
        $toTime = Carbon::parse($time)->addMinutes($total)->subMinute(1)->toTimeString();

        $orderData = collect([
            'date'         => $orderDate,
            'time'         => $orderTime->id,
            'address_id'   => $address->id,
            'items'        => [
                [
                    'category' => $category->id,
                    'package'  => $package1->id,
                    'services' => [$service1->id, $service2->id],
                ],
                [
                    'category' => $category->id,
                    'package'  => $package2->id,
                    'services' => [$service3->id],
                ]
            ],
            'payment_mode' => 'cash',
            'user_id'      => 1
        ]);

        $orderTableData = $orderData->only(['user_id', 'date', 'time'])->toArray();
        $driver1 = $this->_createDriver();
        $driver1->blocked_dates()->create([
            'date' => $orderDate,
            'from' => '09:01:00',
            'to'   => '10:59:00'
        ]);

        $driver2 = $this->_createDriver();

        $response = $this->json('POST', '/api/customer/checkout', $orderData->toArray(), $header);

        $createdOrder = Order::where(['user_id' => $user->id, 'date' => $orderDate])->first();

        $this->assertDatabaseHas('orders', array_merge($orderTableData, ['time'=>$time,'status' => 'success']));
        $this->assertDatabaseHas('blocked_dates',
            [
                'date'      => $orderDate,
                'from'      => '09:01:00',
                'to'        => '10:59:00',
                'driver_id' => $driver1->id
            ]);

        $this->assertDatabaseHas('blocked_dates',
            [
                'date'      => $orderDate,
                'from'      => Carbon::parse($time)->addMinute(1)->toTimeString(),
                'to'        => $toTime,
                'driver_id' => $driver2->id
            ]);

        $this->assertDatabaseHas('jobs', ['order_id' => $createdOrder->id, 'driver_id' => $driver2->id]);

    }


}
