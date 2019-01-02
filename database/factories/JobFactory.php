<?php
use Faker\Generator as Faker;

$factory->define(\App\Models\Job::class, function (Faker $faker) {
    $startTime = \Carbon\Carbon::parse($faker->dateTimeBetween('-1 week', '+2 weeks')->format('Y-m-d'));
    $endTime = $startTime->addHours(rand(2,5));
    return [
        'order_id' =>  \App\Models\Order::first() ? \App\Models\Order::all()->random()->first()->id : 1,
        'driver_id' => \App\Models\Driver::first() ? \App\Models\Driver::all()->random()->first()->id : 1,
        'started_working_at' => $startTime,
        'stopped_working_at' => $endTime,
        'status'      => array_random(['pending'=>'pending','driving'=>'driving','reached'=>'reached','working'=>'working','completed'=>'completed'])
    ];
});

$factory->state(\App\Models\Job::class, 'completed', function (\Faker\Generator $faker) {
    return [
        'status' => 'completed'
    ];
});

$factory->state(\App\Models\Job::class, 'pending', function (\Faker\Generator $faker) {
    return [
        'status' => 'pending',
    ];
});

$factory->state(\App\Models\Job::class, 'working', function (\Faker\Generator $faker) {
    return [
        'status' => 'working',
    ];
});
