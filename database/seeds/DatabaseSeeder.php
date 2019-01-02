<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    protected $tables = [
        'users',
        'categories',
        'drivers',
        'timings',
        'services',
        'order_packages',
        'order_services',
        'packages',
        'services',
        'jobs',
        'cancelled_jobs',
        'blocked_dates',
        'job_photos',
        'areas',
        'addresses',
        'failed_jobs',
        'free_washes',
        'orders',
        'transactions',
        'job_photos'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateTables();
        $this->call(UsersTableSeeder::class);
//         $this->call(CustomersTableSeeder::class);
//         $this->call(VehiclesTableSeeder::class);
        $this->call(DriversTableSeeder::class);
//         $this->call(AddressTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
//         $this->call(PackagesTableSeeder::class);
//         $this->call(ServicesTableSeeder::class);
        $this->call(TimingsTableSeeder::class);
        $this->call(AreasTableSeeder::class);
        $this->call(AddressTableSeeder::class);
         $this->call(OrdersTableSeeder::class);
    }

    public function truncateTables()
    {
        foreach ($this->tables as $table) {
            DB::table($table)->truncate();
        }

    }

}
