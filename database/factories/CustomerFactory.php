<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Customer::class, function (Faker $faker) {

    $isUser = $faker->boolean(50);

    if($isUser) {
        $data = [
            'user_id' => \App\Models\User::all()->where('user_id','!=',1)->random()->first()->id,
        ];
    } else {
        $data = [
            'user_id' => null,
            'name' => $faker->name,
            'mobile' => $faker->phoneNumber,
            'email' => $faker->safeEmail
        ];
    }

    return $data;
});
