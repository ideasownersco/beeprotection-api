<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class DriversTableSeeder extends Seeder
{
    public function run()
    {

        factory(\App\Models\Driver::class,1)->create([
            'offline' =>1,
            'user_id' => function () {
                return factory(User::class)->create(['name'=>'Ali','email'=>'ali@test.com','api_token'=>'12345'])->id;
            },
        ]);

        factory(\App\Models\Driver::class,1)->create([
            'offline' =>1,
            'user_id' => function () {
                return factory(User::class)->create(['name'=>'Abbas','email'=>'abbas@test.com','api_token'=>'12344'])->id;
            },
        ]);

        factory(\App\Models\Driver::class,1)->create([
            'offline' =>1,
            'user_id' => function () {
                return factory(User::class)->create(['name'=>'Mohammad','email'=>'mohammad@test.com','api_token'=>'12343'])->id;
            },
        ]);

        factory(\App\Models\Driver::class,1)->create([
            'user_id' => function () {
                return factory(User::class)->create(['name'=>'Hussain','email'=>'hussain@test.com','api_token'=>'12342'])->id;
            },
        ]);

        factory(\App\Models\Driver::class,1)->create([
            'user_id' => function () {
                return factory(User::class)->create(['name'=>'Nasser','email'=>'nasser@test.com','api_token'=>'12341'])->id;
            },
        ]);


    }
}