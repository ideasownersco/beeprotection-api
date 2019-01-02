<?php

namespace Tests\Feature\Driver;

use App\Models\Timing;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DriverOrdersTest extends TestCase
{

    use RefreshDatabase;


    /**
     *
     * Get the current working job
     */
    public function test_get_working_order()
    {
        $driver = $this->_createDriver();
        $header = $this->_createHeader(['api_token' => $driver->user->api_token]);

        $jobDate = Carbon::today()->toDateString();

        $validOrder = $this->_createOrder([
            'date'   => $jobDate,
            'status' => 'success',
        ]);

        $workingJob = $validOrder->job()->create([
            'driver_id'  => $driver->id,
            'started_working_at' => Carbon::now()->toDateTimeString(),
            'stopped_working_at'   => null,
            'status'     => 'working',
        ]);

        $response = $this->json('GET', '/api/driver/orders/working', [], $header);

        $response->assertJson(['success' => true, 'data' => ['id' => $validOrder->id, 'job' => ['id' => $workingJob->id]]]);

    }


    public function test_driver_gets_upcoming_orders()
    {
        $driver = $this->_createDriver();

        $header = $this->_createHeader(['api_token' => $driver->user->api_token]);

        $validOrder1 = $this->_createOrder([
            'date'   => Carbon::now()->addDays(1)->toDateString(),
            'status' => 'success'
        ]);

        $validOrder2 = $this->_createOrder([
            'date'   => Carbon::now()->addDays(1)->toDateString(),
            'status' => 'success'
        ]);

        $expiredOrder = $this->_createOrder([
            'date'   => Carbon::now()->subDays(1)->toDateString(),
            'status' => 'success'
        ]);

        $expiredOrder1 = $this->_createOrder([
            'date'   => Carbon::now()->subDays(1)->toDateString(),
            'status' => 'success'
        ]);

        $validJob1 = $validOrder1->job()->create(['driver_id' => $driver->id, 'status' => 'pending']);
        $expiredJob = $expiredOrder->job()->create(['driver_id' => $driver->id, 'status' => 'pending']);
        $confirmedJob = $expiredOrder1->job()->create(['driver_id' => $driver->id, 'status' => 'completed']);

        $response = $this->json('GET', '/api/driver/orders/upcoming', [], $header);

        $response->assertJson(['success' => true, 'data' => [['id' => $validOrder1->id]]]);

        $response->assertJsonMissing(['id' => $expiredOrder->id,]);
        $response->assertJsonMissing(['id' => $expiredOrder1->id,]);

    }

    public function test_driver_gets_past_orders()
    {
        $driver = $this->_createDriver();

        $header = $this->_createHeader(['api_token' => $driver->user->api_token]);

        $validOrder1 = $this->_createOrder([
            'date'   => Carbon::now()->addDays(1)->toDateString(),
            'status' => 'success'
        ]);

        $validOrder2 = $this->_createOrder([
            'date'   => Carbon::now()->addDays(1)->toDateString(),
            'status' => 'success'
        ]);

        $expiredOrder1 = $this->_createOrder([
            'date'   => Carbon::now()->subDays(2)->toDateString(),
            'status' => 'success'
        ]);

        $expiredOrder2 = $this->_createOrder([
            'date'   => Carbon::now()->subDays(2)->toDateString(),
            'status' => 'success'
        ]);

        $validOrder1->job()->create(['driver_id' => $driver->id, 'status' => 'working']);
        $validOrder2->job()->create(['driver_id' => $driver->id, 'status' => 'working']);
        $expiredOrder1->job()->create(['driver_id' => $driver->id, 'status' => 'completed']);
        $expiredOrder2->job()->create(['driver_id' => $driver->id, 'status' => 'completed']);

        $response = $this->json('GET', '/api/driver/orders/past', [], $header);

        $response->assertJson(['success' => true, 'data' => [['id' => $expiredOrder2->id], ['id' => $expiredOrder1->id]]]);

        $response->assertJsonMissing(['id' => $validOrder1->id,]);
        $response->assertJsonMissing(['id' => $validOrder2->id,]);

    }


}
