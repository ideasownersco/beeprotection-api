<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Area::class, function (Faker $faker) {
    return [
        'country_id' => 1,
        'name_ar' => $faker->city,
        'name_en' => $faker->city,
    ];
});

