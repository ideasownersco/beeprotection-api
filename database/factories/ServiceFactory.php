<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Service::class, function (Faker $faker) {
    return [
        'package_id' => \App\Models\Package::all()->random()->first()->id,
        'name_en' => $faker->word,
        'name_ar' => $faker->word,
        'image' => asset('/uploads/test.jpg'),
        'price' => rand(5,25),
        'duration' => 15,
    ];
});
