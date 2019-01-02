<?php

namespace Tests\Feature\Api;

use App\Exceptions\DriverIsBusyException;
use App\Models\Category;
use App\Models\Driver;
use App\Models\Job;
use App\Models\Location;
use App\Models\Order;
use App\Models\Package;
use App\Models\Service;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriesControllerTest extends TestCase
{

    use RefreshDatabase;

    public function test_get_categories_with_packages_services()
    {
        $categories = factory(Category::class,5)->create()->each(function($category) {
            factory(Package::class,3)->create(['category_id'=>$category->id])->each(function($package) {
                factory(Service::class,5)->create(['package_id'=>$package->id]);
            });
        });

        $inActiveCategory = factory(Category::class)->create(['active'=>0]);

        $user = $this->_createUser(['admin' => 1]);
        $header = $this->_createHeader(['api_token' => $user->api_token]);

        $order = factory(Order::class)->create();

        $response = $this->json('GET', '/api/categories');

        $this->assertDatabaseHas('orders', ['id' => $order->id]);

    }



}
