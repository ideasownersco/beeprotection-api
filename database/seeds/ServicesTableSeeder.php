<?php
use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    public function run()
    {
        factory(\App\Models\Service::class,3)->create();
    }
}