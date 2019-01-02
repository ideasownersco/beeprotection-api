<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Holiday::class, function (Faker $faker) {
    return [
        'name_en' => $faker->city,
        'name_ar' => $faker->city,
    ];
});
