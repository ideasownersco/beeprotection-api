<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Category::class, function (Faker $faker) {
    return [
        'name_en' => $faker->word,
        'name_ar' => $faker->word,
        'image' => asset('/uploads/test.jpg'),

    ];
});
