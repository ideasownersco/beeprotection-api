<?php
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // TODO: Implement run() method.
        $user1 = factory(\App\Models\User::class)->create(['email' => 'admin@test.com', 'password' => bcrypt('secret'), 'admin'=>1, 'active' => 1,'api_token'=>'54321']);
        $customer1 = factory(\App\Models\User::class)->create(['email' => 'customer@test.com', 'password' => bcrypt('secret'), 'active' => 1,'api_token'=>'111111']);
//        $driver = factory(\App\Models\User::class)->create(['email' => 'driver@test.com', 'password' => bcrypt('secret'), 'active' => 1]);
//        factory(\App\Models\User::class, 2)->create();

//        $driver->driver()->create();
//        factory(\App\Models\Address::class)->create(['user_id'=>$customer1->id]);

    }
}