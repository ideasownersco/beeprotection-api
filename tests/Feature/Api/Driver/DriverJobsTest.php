<?php

namespace Tests\Feature\Driver;

use App\Events\StartedDriving;
use App\Events\StoppedDriving;
use App\Events\StoppedWorking;
use App\Events\StartedWorking;
use App\Models\Timing;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class DriverJobsTest extends TestCase
{

    use RefreshDatabase;


//    public function test_driver_trips_excludes_expired_jobs()
//    {
//        $driver = $this->_createDriver();
//
//        $header = $this->_createHeader(['api_token' => $driver->user->api_token]);
//
//        $validOrder = $this->_createOrder([
//            'date' => Carbon::now()->addDays(1)->toDateString(),
//        ]);
//
//        $expiredOrder = $this->_createOrder([
//            'date' => Carbon::now()->subDays(1)->toDateString(),
//        ]);
//
//        $validJob = $validOrder->jobs()->create(['driver_id' => $driver->id,'accepted'=>1,'status'=>'pending']);
//        $expiredJob = $expiredOrder->jobs()->create(['driver_id' => $driver->id,'accepted'=>1,'status'=>'pending']);
//
//        $response = $this->json('GET', '/api/driver/jobs', [], $header);
//
//        $response->assertJson(['success'=>true,'data'=>[['id'=>$validJob->id]]]);
//
//        $missingJsonFragment = ['id'=>$expiredJob->id];
//
//        $response->assertJsonMissing($missingJsonFragment);
//
//    }

    public function test_driver_starts_driving_to_order_location()
    {
        Event::fake();
        $customer = $this->_createCustomer();
        $customer->push_tokens()->create(['token' => 'f758b696281180f131c31389dc19eecd32f88724dc0d1cf1aae0693c731c0da1']);
        $driver = $this->_createDriver();
        $order = $this->_createOrder();
        $order->create();
        $job = $order->job;
        $job->startDriving();

        Event::assertDispatched(StartedDriving::class, function ($e) use ($job) {
            return $e->job->id === $job->id;
        });

        $this->assertDatabaseHas('jobs', ['id' => $job->id, 'status' => 'driving']);
    }

    public function test_driver_reached_destination()
    {
        Event::fake();
        $customer = $this->_createCustomer();
        $customer->push_tokens()->create(['token' => 'f758b696281180f131c31389dc19eecd32f88724dc0d1cf1aae0693c731c0da1']);
        $driver = $this->_createDriver();
        $order = $this->_createOrder();
        $order->create();
        $job = $order->job;
        $job->stopDriving();

        Event::assertDispatched(StoppedDriving::class, function ($e) use ($job) {
            return $e->job->id === $job->id;
        });

        $this->assertDatabaseHas('jobs', ['id' => $job->id, 'status' => 'reached']);
    }


    public function test_driver_starts_working_on_an_order()
    {
        Event::fake();
        $customer = $this->_createCustomer();
        $customer->push_tokens()->create(['token'=>'f758b696281180f131c31389dc19eecd32f88724dc0d1cf1aae0693c731c0da1']);
        $driver = $this->_createDriver();
        $order = $this->_createOrder();
        $order->create();
        $job = $order->job;
        $job->startWorking();

        Event::assertDispatched(StartedWorking::class, function ($e) use ($job) {
            return $e->job->id === $job->id;
        });

        $this->assertDatabaseHas('jobs',['id'=>$job->id,'status'=>'working']);

    }

    public function test_driver_stops_working_on_an_order()
    {
        Event::fake();
        $customer = $this->_createCustomer();
        $customer->push_tokens()->create(['token'=>'f758b696281180f131c31389dc19eecd32f88724dc0d1cf1aae0693c731c0da1']);
        $driver = $this->_createDriver();
        $order = $this->_createOrder();
        $order->create();
        $job = $order->job;
        $job->stopWorking();

        Event::assertDispatched(StoppedWorking::class, function ($e) use ($job) {
            return $e->job->id === $job->id;
        });

        $this->assertDatabaseHas('jobs',['id'=>$job->id,'status'=>'completed']);

    }


}
