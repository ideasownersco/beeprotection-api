<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Area;
use App\Models\BlockedDate;
use App\Models\Category;
use App\Models\Order;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateAddressTest extends TestCase
{

    use RefreshDatabase;

    public function test_user_can_create_successful_cash_order()
    {
        $user = $this->_createCustomer();
        $header = $this->_createHeader(['api_token' => $user->api_token]);

        $orderData = collect([
            'latitude' => 29.3759,
            'longitude' => 47.9774,
        ]);

        $response = $this->json('POST', '/api/customer/addresses', $orderData->toArray(), $header);


        dd('wa');

    }


}
