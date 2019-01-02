<?php

namespace Tests\Unit\Driver;

use App\Models\BlockedDate;
use App\Models\Driver;
use App\Models\Job;
use App\Models\Order;
use App\Models\Timing;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderDriverAssignTest extends TestCase
{

    use RefreshDatabase;

    /**
     *
     * Get the current working job
     */
    public function test_system_automatically_assigns_orders_for_driver()
    {

        $user = $this->_createCustomer();
        $header = $this->_createHeader(['api_token' => $user->api_token]);

        $ali = $this->_createDriver();
        $mohammad = $this->_createDriver();
        $abbas = $this->_createDriver();

        $orderData1 = ['invoice'=>uniqid(),'time' => '09:00:00','status'=>'checkout','payment_mode'=>'cash'];
        $orderData2 = ['invoice'=>uniqid(),'time' => '09:00:00','status'=>'checkout','payment_mode'=>'cash'];
        $orderData3 = ['invoice'=>uniqid(),'time' => '09:00:00','status'=>'checkout','payment_mode'=>'cash'];
        // busy until 10am = 60min

       /** First Three Orders Should Evenly Distribute to Drivers*/
        $order1 = $this->_createOrder($orderData1);
        $order2 = $this->_createOrder($orderData2);
        $order3 = $this->_createOrder($orderData3);

        $order1->create();
        $order2->create();
        $order3->create();

        $order1Driver = $order1->job->driver;
        $order2Driver = $order2->job->driver;
        $order3Driver = $order3->job->driver;

        $this->assertDatabaseHas('jobs',['order_id' => $order1->id]);
        $this->assertDatabaseHas('jobs',['order_id' => $order2->id]);
        $this->assertDatabaseHas('jobs',['order_id' => $order3->id]);


        $order1From = Carbon::parse($order1->time)->addMinute(1)->toTimeString();
        $order1To = Carbon::parse($order1->calculateDuration())->subMinute(1)->toTimeString();
        $this->assertDatabaseHas('blocked_dates',['driver_id' => $order1Driver->id,'from'=>$order1From,'to'=>$order1To]);

        $firstThreeOrders = collect([$order1Driver->id,$order2Driver->id,$order3Driver->id])->unique();
        $this->assertEquals(3,$firstThreeOrders->count());

        /** Fourth Order */

        $orderData4 = ['invoice'=>uniqid(),'time' => '10:30:00','status'=>'checkout','payment_mode'=>'cash'];
        $orderData5 = ['invoice'=>uniqid(),'time' => '11:00:00','status'=>'checkout','payment_mode'=>'cash'];
        $orderData6 = ['invoice'=>uniqid(),'time' => '12:00:00','status'=>'checkout','payment_mode'=>'cash'];
        $orderData7 = ['invoice'=>uniqid(),'time' => '15:00:00','status'=>'checkout','payment_mode'=>'cash'];

        $order4 = $this->_createOrder($orderData4,['duration' => 70]);
        // busy from 10:30 - 12:30

        $order5 = $this->_createOrder($orderData5,['duration' => 430]);
        // busy from 11:00 - 19:00

        $order6 = $this->_createOrder($orderData6,['duration' => 310]);
        // busy from 12:00 - 18:00

        $order7 = $this->_createOrder($orderData7);
        // busy from 15:00 - 16:00 // this order must go to driver 4

        $order4->create();
        $order5->create();
        $order6->create();
        $order7->create();

        $order4Driver = $order4->job->driver;
        $order5Driver = $order5->job->driver;
        $order6Driver = $order6->job->driver;
        $order7Driver = $order7->job->driver;

        $this->assertDatabaseHas('jobs',['order_id' => $order4->id]);
        $this->assertDatabaseHas('jobs',['order_id' => $order5->id]);
        $this->assertDatabaseHas('jobs',['order_id' => $order6->id]);
        $this->assertDatabaseHas('jobs',['order_id' => $order7->id]);

        $this->assertDatabaseHas('drivers',['id' => $order4Driver->id,'job_count'=>3]);
        $this->assertDatabaseHas('drivers',['id' => $order5Driver->id,'job_count'=>2]);
        $this->assertDatabaseHas('drivers',['id' => $order6Driver->id,'job_count'=>2]);

        $orders4to6 = collect([$order4Driver->id,$order5Driver->id,$order6Driver->id])->unique();

        $this->assertEquals(3,$orders4to6->count());

        $this->assertEquals($order4Driver->id,$order7Driver->id);

        /** @var Third State Order $order8 */

//        $hussain = $this->_createDriver();
//        $naser = $this->_createDriver();
//
//        $orderData8 = ['invoice'=>uniqid(),'time' => '9:00:00','status'=>'checkout','payment_mode'=>'cash'];
//        $orderData9 = ['invoice'=>uniqid(),'time' => '12:00:00','status'=>'checkout','payment_mode'=>'cash'];
//        $orderData10 = ['invoice'=>uniqid(),'time' => '13:00:00'];
//
//        $order8 = $this->_createOrder($orderData8,['duration'=>130]);
//        $order9 = $this->_createOrder($orderData9,['duration' => 70]);
//        $order10 = $this->_createOrder($orderData10,['duration' => 190]);
//
//        $order8->create();
//        $order9->create();
//        $order10->create();
//
//        $this->assertDatabaseHas('jobs',['order_id' => $order8->id,]);
//        $this->assertDatabaseHas('jobs',['order_id' => $order9->id]);
//        $this->assertDatabaseHas('jobs',['order_id' => $order10->id]);
//

    }
}
