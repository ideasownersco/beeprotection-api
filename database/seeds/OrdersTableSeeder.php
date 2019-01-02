<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {

        $validOrder = factory(\App\Models\Order::class)
            ->create([
                'status' => 'success',
                'date'   => \Carbon\Carbon::today()->toDateString(),
                'time'   => '15:00:00',
            ]);

        $packagesCount = rand(1, 3);
        $packages = \App\Models\Package::limit($packagesCount)->get();

        // calculate price total
        // initialize with 0
        $validOrder->total = 0;

        $packages->map(function ($package) use ($validOrder) {

            // add up package total
            $validOrder->total += $package->price;

            $hasService = rand(0, 1);

            $validOrder->packages()->attach($package->id, ['price' => $package->price]);

            if ($hasService) {
                $servicesCount = rand(1, 3);
                $services = \App\Models\Service::limit($servicesCount)->get();
                $services->map(function ($service) use ($validOrder) {

                    // add up service total
                    $validOrder->total += $service->price;

                    $validOrder->services()->attach($service->id, ['price' => $service->price]);
                });
            }

            $validOrder->save();
        });

        $driver = \App\Models\Driver::where('offline', 0)->orderBy(DB::raw('RAND()'))->first();

        $job = factory(\App\Models\Job::class)->create([ 'status' => 'pending', 'driver_id' => $driver->id, 'stopped_working_at' => null]);
        $transaction = factory(\App\Models\Transaction::class)->create(['amount' => $validOrder->total]);
        $job->update(['order_id' => $validOrder->id]);

        $validOrder->transaction()->save($transaction);

//        $validOrder2 = factory(\App\Models\Order::class)
//            ->create([
//                'status' => 'success',
//                'date'   => \Carbon\Carbon::now()->addDays(rand(1, 2))->toDateString()
//            ]);
//
//        $job1 = factory(\App\Models\Job::class)->create([ 'status' => 'working', 'driver_id' => $driver->id, 'stopped_working_at' => null]);
//        $transaction = factory(\App\Models\Transaction::class)->create(['amount' => $validOrder2->total]);
//        $job1->update(['order_id' => $validOrder2->id]);
//        $validOrder2->transaction()->save($transaction);

    }
}