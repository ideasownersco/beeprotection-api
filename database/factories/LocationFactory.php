<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Location::class, function (Faker $faker) {

    $latitude =  mt_rand(29.10,29.90).'.'.mt_rand(10,100);
    $longitude = mt_rand(47.10,47.90).'.'.mt_rand(10,100);

    return [
        'customer_id' => \App\Models\Customer::all()->count() > 0 ? \App\Models\Customer::all()->random()->first()->id : 1,
        'latitude' => $latitude,
        'longitude' => $longitude
    ];
});
