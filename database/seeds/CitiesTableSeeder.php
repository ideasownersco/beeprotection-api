<?php
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    public function run()
    {
        factory(\App\Models\Holiday::class,10)->create();
    }
}