<?php

namespace Tests\Feature\Driver;

use App\Models\Address;
use App\Models\Timing;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerOrderTest extends TestCase
{

    use RefreshDatabase;


    public function test_get_working_order()
    {
        $customer = $this->_createCustomer();
        $driver = $this->_createDriver();

        $header = $this->_createHeader(['api_token' => $customer->api_token]);

        $jobDate = Carbon::today()->toDateString();

        $validOrder = $this->_createOrder([
            'date' => $jobDate,
            'status' => 'success',
        ]);

        $pendingOrder = $this->_createOrder([
            'date' => $jobDate,
            'status' => 'pending',
        ]);

        $workingJob = $validOrder->job()->create([
            'driver_id'=>$driver->id,
            'started_working_at' => Carbon::now()->toDateTimeString(),
            'stopped_working_at' => null,
            'status' => 'working',
        ]);

        $response = $this->json('GET', '/api/customer/orders/working', [], $header);

        $response->assertJson(['success'=>true,'data'=>['id'=>$validOrder->id,'job'=>['id'=>$workingJob->id]]]);

        $response->assertJsonMissing(['id'=>$pendingOrder->id]);
    }

    public function test_get_ignores_finished_job_for_working_order()
    {
        $customer = $this->_createCustomer();
        $driver = $this->_createDriver();

        $header = $this->_createHeader(['api_token' => $customer->api_token]);

        $jobDate = Carbon::today()->toDateString();

        $completedOrder = $this->_createOrder([
            'date' => $jobDate,
            'status' => 'success',
        ]);

        $validOrder = $this->_createOrder([
            'date' => $jobDate,
            'status' => 'success',
        ]);

        $completedJob = $completedOrder->job()->create([
            'driver_id'=>$driver->id,
            'started_working_at' => Carbon::now()->toDateTimeString(),
            'stopped_working_at' => null,
            'status' => 'completed',
        ]);

        $validOrder = $validOrder->job()->create([
            'driver_id'=>$driver->id,
            'started_working_at' => Carbon::now()->toDateTimeString(),
            'stopped_working_at' => null,
            'status' => 'working',
        ]);

        $response = $this->json('GET', '/api/customer/orders/working', [], $header);

        $response->assertJson(['success'=>true,'data'=>['id'=>$validOrder->id]]);

        $response->assertJsonMissing(['id'=>$completedOrder->id]);
    }

    public function test_customer_gets_upcoming_orders()
    {
        $customer = $this->_createCustomer();

        $header = $this->_createHeader(['api_token' => $customer->api_token]);

        $pendingOrder = $this->_createOrder([
            'date' => Carbon::now()->addDays(1)->toDateString(),
            'user_id' => $customer->id,
            'status' => 'success'
        ]);

        $workingOrder = $this->_createOrder([
            'date' => Carbon::now()->addDays(1)->toDateString(),
            'user_id' => $customer->id,
            'status' => 'success'
        ]);

        $confirmedOrder = $this->_createOrder([
            'date' => Carbon::now()->addDays(1)->toDateString(),
            'user_id' => $customer->id,
            'status' => 'success'
        ]);

        $expiredOrder = $this->_createOrder([
            'date' => Carbon::now()->subDays(3)->toDateString(),
            'user_id' => $customer->id,
            'status' => 'success'
        ]);

        $pendingJob = $pendingOrder->job()->create(['driver_id' => $customer->id,'status'=>'pending']);
        $workingJob = $workingOrder->job()->create(['driver_id' => $customer->id,'status'=>'working']);
        $confirmedJob = $confirmedOrder->job()->create(['driver_id' => $customer->id,'status'=>'completed']);
        $expiredJob = $expiredOrder->job()->create(['driver_id' => $customer->id,'status'=>'completed']);

        $response = $this->json('GET', '/api/customer/orders/upcoming', [], $header);

        $response->assertJson(['success'=>true,'data'=>[['id'=>$pendingOrder->id]]]);

        $response->assertJsonMissing(['id'=>$confirmedOrder->id,]);
        $response->assertJsonMissing(['id'=>$expiredOrder->id,]);
        $response->assertJsonMissing(['id'=>$workingOrder->id,]);

    }


}
