<?php

namespace Tests\Feature\Api;

use App\Exceptions\DriverIsBusyException;
use App\Models\Area;
use App\Models\Category;
use App\Models\Driver;
use App\Models\Job;
use App\Models\Location;
use App\Models\Order;
use App\Models\Package;
use App\Models\Service;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestController extends TestCase
{

    use RefreshDatabase;


    public function test_controller()
    {

        $area = factory(Area::class)->create();
        $area = factory(Area::class)->create();
        $area = factory(Area::class)->create();
        $area = factory(Area::class)->create();

        $cities = Area::all()->pluck('name_en');

        foreach($cities as $city) {
            $city = urlencode($city);
            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$city.'&sensor=false';
            $response = $this->get($url);
            dd($response);
        }


        $response = $this->get('/api/test');

        dd($cities->toArray());
        dd($response);
    }


}
