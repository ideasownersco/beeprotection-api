<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Address::class, function (Faker $faker) {
    return [
        'city_ar' => $faker->city,
        'city_en' => $faker->city,
        'state_ar' => $faker->city,
        'state_en' => $faker->city,
        'address_en' => $faker->address,
        'address_ar' => $faker->address,
        'block' => rand(1,5),
        'street' => rand(1,5),
        'avenue' => rand(1,5),
        'building' => rand(1,5),
//        "latitude" => 37.37166518,
//        "longitude" => -122.217832462,
        "latitude" => mt_rand(37.37166518,37.37166900),
        "longitude" => mt_rand(-122.217832462,-122.217832800),
//        "latitude" => mt_rand(29.10,29.90).'.'.mt_rand(10,100),
//        "longitude" => mt_rand(47.10,47.90).'.'.mt_rand(10,100),
    ];
});

