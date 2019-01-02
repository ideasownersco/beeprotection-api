<?php
use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {

        $customer1 = factory(\App\Models\Customer::class)->create(['user_id'=>1]);

//        factory(\App\Models\Customer::class,3)->create();

//        factory(\App\Models\Address::class)->create(['customer_id'=>$customer1->id]);

    }
}