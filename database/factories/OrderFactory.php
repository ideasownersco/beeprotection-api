<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Order::class, function (Faker $faker) {
    return [
        'user_id'    => \App\Models\User::first() ? \App\Models\User::all()->random()->first()->id : 1,
        'address_id' => \App\Models\Address::first() ? \App\Models\Address::all()->random()->first()->id : 1,
        'total'      => rand(100, 500),
        'date'       => \Carbon\Carbon::today()->format('Y-m-d'),
        'time'       => '09:00:00'
//        'date'       => \Carbon\Carbon::parse($faker->dateTimeBetween('+1 week', '+2 weeks')->format('Y-m-d')),
//        'time'       => \Carbon\Carbon::parse($faker->dateTimeBetween('-1 week', '+2 weeks'))->toTimeString()
    ];
});
