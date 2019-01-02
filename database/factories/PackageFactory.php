<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Package::class, function (Faker $faker) {
    return [
        'category_id' => \App\Models\Category::all()->random()->first()->id,
        'name_en' => $faker->word,
        'name_ar' => $faker->word,
        'description_en' => $faker->sentence,
        'description_ar' => $faker->sentence,
        'price' => rand(10,100),
        'image' => asset('/uploads/test.jpg'),
        'duration' => 30,
    ];
});
