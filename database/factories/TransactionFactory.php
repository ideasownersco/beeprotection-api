<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Transaction::class, function (Faker $faker) {
    return [
        'order_id'       => \App\Models\Order::all()->random()->first()->id,
        'amount'         => '100',
        'payment_method' => 'VISA',
        'status'         => array_random(['pending' => 'pending', 'completed' => 'completed', 'cancelled' => 'cancelled'])
    ];
});
