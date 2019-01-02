<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Timing::class, function (Faker $faker) {
    return [
        'name_en' => $faker->time(),
        'name_ar' => $faker->time(),
    ];
});
