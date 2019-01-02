<?php
use App\Models\Category;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Database\Seeder;

class TimingsTableSeeder extends Seeder
{
    public function run()
    {
        factory(\App\Models\Timing::class)->create(['name_en'=>'09:00:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'09:30:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'10:00:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'10:30:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'11:00:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'11:30:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'12:00:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'12:30:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'13:00:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'13:30:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'14:00:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'14:30:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'15:00:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'15:30:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'16:00:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'16:30:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'17:00:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'17:30:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'18:00:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'18:30:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'19:00:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'19:30:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'20:00:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'20:30:00']);
        factory(\App\Models\Timing::class)->create(['name_en'=>'21:00:00']);
    }
}