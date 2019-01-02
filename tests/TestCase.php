<?php

namespace Tests;

use App\Models\Address;
use App\Models\Category;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Package;
use App\Models\Service;
use App\Models\Timing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function _createHeader($array)
    {
        $headers['Authorization'] = 'Bearer ' . $array['api_token'];
        return $headers;
    }


    public function _createUser($array = [])
    {
        return factory(User::class)->create($array);
    }

    public function _createAdmin($array = [])
    {
        return factory(User::class)->create(array_merge([
            'admin' => 1
        ], $array));
    }

    public function _createCustomer($array = [])
    {
        return factory(User::class)->create(array_merge([
            'admin' => 0
        ], $array));
    }

    public function _createDriver($array = [])
    {
        $driver = factory(Driver::class)->create(array_merge([
            'user_id' => function () {
                return factory(User::class)->create()->id;
            },
        ], $array));

        return $driver;
    }

    public function _createOrder($array = [],$package = [],$service=[])
    {
        $postData = collect($this->_createOrderPostData($array));

        $orderDataTrimmed = $postData->except('items','date','time')->toArray();
        $dateDataTrimmed = $postData->only('date','time')->toArray();

        $date = Carbon::parse($dateDataTrimmed['date'])->toDateString();

        $time = Carbon::parse($dateDataTrimmed['time'])->toTimeString();

        $orderDataTrimmed['date'] = $date;
        $orderDataTrimmed['time'] = $time;

        $order = factory(Order::class)->create(array_merge($orderDataTrimmed));

        $package1 = factory(Package::class)->create(array_merge(['duration'=>10],$package));
        $service1 = factory(Service::class)->create(array_merge(['duration'=>10],$service));
        $service2 = factory(Service::class)->create(array_merge(['duration'=>10],$service));

        $order->packages()->sync([$package1->id]);
        $order->services()->sync([$service1->id,$service2->id]);

        return $order;
    }

    public function _createOrderPostData($array = [])
    {
        $address = factory(Address::class)->create();

        $orderDate = Carbon::today()->toDateString();
        $orderTime = Carbon::now()->addHours(rand(1,5))->format('H:m');

        $category = factory(Category::class)->create();
        $package = factory(Package::class)->create(['category_id' => $category->id]);
        $service1 = factory(Service::class)->create(['package_id' => $package->id]);
        $service2 = factory(Service::class)->create(['package_id' => $package->id]);

        $postData = [
            'date'    => $orderDate,
            'time'    => $orderTime,
//            'total' => 200,
            'address_id' => $address->id,
            'items'   => [
                [
                    'category' => $category->id,
                    'package'  => $package->id,
                    'services' => [$service1->id, $service2->id],
                    'total' => 100
                ]
            ],
            'payment_mode' => 'cash',
            'user_id' => 1
        ];

        return array_merge($postData, $array);
    }

    public function _createTiming($time)
    {
        return factory(Timing::class)->create(['name_en'=>$time,'name_ar'=>$time]);
    }

}
