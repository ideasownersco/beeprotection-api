<?php
use Illuminate\Database\Seeder;

class AddressTableSeeder extends Seeder
{
    public function run()
    {
        factory(\App\Models\Address::class,1)->create([
            'latitude'=>'29.2965','longitude'=>'48.0794',
            'area_id' => \App\Models\Area::where('name_en','salwa')->first()->id
        ]);
    }
}