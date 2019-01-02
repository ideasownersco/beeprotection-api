<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Driver::class, function (Faker $faker) {
    return [
        'user_id' => \App\Models\User::first() ?  \App\Models\User::all()->random()->first()->id : 1,
        'offline' => 0,
        'active' => 1,
        'start_time' => '05:00:00',
        'end_time' => '23:00:00'
    ];
});

//
//$factory->state(\App\Models\Driver::class, 'available', function (\Faker\Generator $faker) {
//    return [
//        'status' => 'available'
//    ];
//});
//
//$factory->state(\App\Models\Driver::class, 'offline', function (\Faker\Generator $faker) {
//    return [
//        'status' => 'offline'
//    ];
//});