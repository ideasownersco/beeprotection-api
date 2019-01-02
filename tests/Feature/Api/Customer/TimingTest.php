<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\BlockedDate;
use App\Models\Category;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TimingTest extends TestCase
{

    use RefreshDatabase;

    public function test_customer_gets_valid_timings()
    {
        $user = $this->_createCustomer();
        $user->push_tokens()->create(['token' => 'f758b696281180f131c31389dc19eecd32f88724dc0d1cf1aae0693c731c0da1']);

        $header = $this->_createHeader(['api_token' => $user->api_token]);

        $driver1= $this->_createDriver();
        $driver2= $this->_createDriver();

        $timing1 = $this->_createTiming('10:00:00');
        $timing2 = $this->_createTiming('11:00:00');
        $timing3 = $this->_createTiming('14:45:00');
        $timing4 = $this->_createTiming('10:30:00');
        $timing5 = $this->_createTiming('12:30:00'); //valid // in between
        $timing6 = $this->_createTiming('14:30:00');
        $timing7 = $this->_createTiming('08:00:00'); //valid // before
        $timing8 = $this->_createTiming('17:00:00'); //valid // after

        $orderDate = Carbon::today()->toDateString();

        $category = factory(Category::class)->create();
        $package = factory(Package::class)->create(['category_id' => $category->id]);
        $service1 = factory(Service::class)->create(['package_id' => $package->id]);
        $service2 = factory(Service::class)->create(['package_id' => $package->id]);

        $order1=$this->_createOrder(['date'=>$orderDate,'time'=>$timing1->name_en],['duration'=>$package->duration],['duration'=>$service1->duration]);
        $order2=$this->_createOrder(['date'=>$orderDate,'time'=>$timing1->name_en],['duration'=>$package->duration],['duration'=>$service1->duration]);
        $order3=$this->_createOrder(['date'=>$orderDate,'time'=>$timing3->name_en],['duration'=>$package->duration],['duration'=>$service1->duration]);
        $order4=$this->_createOrder(['date'=>$orderDate,'time'=>$timing3->name_en],['duration'=>$package->duration],['duration'=>$service1->duration]);
        $order5=$this->_createOrder(['date'=>$orderDate,'time'=>$timing5->name_en],['duration'=>$package->duration],['duration'=>$service1->duration]);

        $order1->create();
        $order2->create();
        $order3->create();
        $order4->create();
        $order5->create();

        $items = [
            [
                'category' => $category->id,
                'package'  => $package->id,
                'total'    => 100
            ],
            [
                'category' => $category->id,
                'package'  => $package->id,
                'services' => [$service1->id, $service2->id],
                'total'    => 100
            ]
        ]; //total duration 120 mins

        $response = $this->json('POST', '/api/timings', ['date' => $orderDate,'items' => $items], $header);


        $response->assertJson(['data'=>[
            ['id'=>$timing1->id,'disabled'=>true],
            ['id'=>$timing2->id,'disabled'=>true],
            ['id'=>$timing3->id,'disabled'=>true],
            ['id'=>$timing4->id,'disabled'=>true],
            ['id'=>$timing5->id,'disabled'=>false],
            ['id'=>$timing6->id,'disabled'=>true],
            ['id'=>$timing7->id,'disabled'=>false],
            ['id'=>$timing8->id,'disabled'=>false],
        ]]);

//        dd($response->json());

    }


}
