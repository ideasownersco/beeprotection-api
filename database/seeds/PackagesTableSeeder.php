<?php
use Illuminate\Database\Seeder;

class PackagesTableSeeder extends Seeder
{
    public function run()
    {
        factory(\App\Models\Package::class,3)->create();
    }
}