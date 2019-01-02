<?php

use App\Models\Category;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
//        factory(\App\Models\Category::class,3)->create();

        factory(Category::class, 1)->create(['name_en' => 'Small','image' => asset('uploads/car-small.png')])->each(function ($category) {
            $package1 = factory(Package::class, 1)->create(['image'=>asset('uploads/package1.png'),'name_en' => 'Bee Wash', 'name_ar' => 'Bee Wash', 'description_en'=>'Exterior wash & Interior wash','description_ar'=>'غسيل خارجي + تنظيف داخلي ','category_id' => $category->id,'price'=>5.25])->each(function ($package) {
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Carpets',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/wheel.png'),
                        'name_en' => 'Tire Polish',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Engine',
                        'price' => 3.5,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Seats',
                        'price' => 5.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Stain Removal',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Cargo',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Polish',
                        'price' => 28,
                        'duration' => 180,
                        'package_id' => $package->id
                    ]
                );
            });

            $package2 = factory(Package::class, 1)->create(['image'=>asset('uploads/package2.png'),'name_en' => 'Bee Wax', 'name_ar' => 'Bee Wax', 'description_en'=>'Exterior wash - Interior- Wax - Tire & wheels polish - interior- floor','description_ar'=>'غسيل خارجي+ واكس + تلميع الإطارات والرنقات + تنظيف داخلي + إزالة بقع الأرضية','price'=>12.5,'category_id' => $category->id])->each(function ($package) {

                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Carpets',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/wheel.png'),
                        'name_en' => 'Tire Polish',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Engine',
                        'price' => 3.5,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Seats',
                        'price' => 5.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Stain Removal',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Cargo',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Polish',
                        'price' => 28,
                        'duration' => 180,
                        'package_id' => $package->id
                    ]
                );
            });
            $package3 = factory(Package::class, 1)->create(['image'=>asset('uploads/package3.png'),'name_en' => 'Bee Protect', 'name_ar' => 'Bee Protect', 'description_en'=>'Exterior wash - interior- Wax - Tire & wheels polish - interior- floor- engine ','description_ar'=>'غسيل خارجي + تنظيف داخلي + واكس + تلميع الإطارات والرنقات + إزالة بقع الأرضية + مكينة السيارة','price'=>15.5,'category_id' => $category->id])->each(function ($package) {

                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Carpets',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id,
                        'included' => 1
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/wheel.png'),
                        'name_en' => 'Tire Polish',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id,

                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Engine',
                        'price' => 3.5,
                        'duration' => 15,
                        'package_id' => $package->id,
                        'included' => 1
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Seats',
                        'price' => 5.5,
                        'duration' => 30,
                        'package_id' => $package->id,
                        'included' => 1
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Stain Removal',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Cargo',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Polish',
                        'price' => 28,
                        'duration' => 180,
                        'package_id' => $package->id
                    ]
                );
            });

            $package4 = factory(Package::class, 1)->create(['image'=>asset('uploads/package3.png'),'name_en' => 'Queen Wash', 'name_ar' => 'Queen Wash', 'description_en'=>'Polish - Exterior wash -  full interior- Wax - Tire & wheels polish - interior- floor- engine','description_ar'=>'
Queen Wash : بوليش ٣ طبقات + غسيل خارجي + واكس+ تنظيف داخلي شامل إزالة البقع بالكامل شامل المقاعد وتلميع الديكورات - تلميع الإطارات والرنقات ','price'=>34.75,'category_id' => $category->id])->each(function ($package) {

                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Carpets',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/wheel.png'),
                        'name_en' => 'Tire Polish',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Engine',
                        'price' => 3.5,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Seats',
                        'price' => 5.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Stain Removal',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Cargo',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Polish',
                        'price' => 28,
                        'duration' => 180,
                        'package_id' => $package->id
                    ]
                );
            });
        });


        /**
         * |--------------------------------------------------------------------------
         * | xxx
         * |--------------------------------------------------------------------------
         */


        factory(Category::class, 1)->create(['name_en' => 'Medium','image' => asset('uploads/car-medium.png')])->each(function ($category) {
            $package1 = factory(Package::class, 1)->create(['image'=>asset('uploads/package1.png'),'name_en' => 'Bee Wash', 'name_ar' => 'Bee Wash', 'description_en'=>'Exterior wash & Interior wash','description_ar'=>'غسيل خارجي + تنظيف داخلي ','category_id' => $category->id,'price'=>6.5])->each(function ($package) {
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Carpets',
                        'price' => 3.25,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/wheel.png'),
                        'name_en' => 'Tire Polish',
                        'price' => 3.25,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Engine',
                        'price' => 4.5,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Seats',
                        'price' => 7.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Stain Removal',
                        'price' =>  7.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Cargo',
                        'price' => 3.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Polish',
                        'price' => 35.5,
                        'duration' => 180,
                        'package_id' => $package->id
                    ]
                );
            });

            $package2 = factory(Package::class, 1)->create(['image'=>asset('uploads/package2.png'),'name_en' => 'Bee Wax', 'name_ar' => 'Bee Wax', 'description_en'=>'Exterior wash - Interior- Wax - Tire & wheels polish - interior- floor','description_ar'=>'غسيل خارجي+ واكس + تلميع الإطارات والرنقات + تنظيف داخلي + إزالة بقع الأرضية','price'=>15.5,'category_id' => $category->id])->each(function ($package) {

                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Carpets',
                        'price' => 3.25,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/wheel.png'),
                        'name_en' => 'Tire Polish',
                        'price' => 3.25,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Engine',
                        'price' => 4.5,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Seats',
                        'price' => 7.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Stain Removal',
                        'price' =>  7.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Cargo',
                        'price' => 3.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Polish',
                        'price' => 35.5,
                        'duration' => 180,
                        'package_id' => $package->id
                    ]
                );
            });
            $package3 = factory(Package::class, 1)->create(['image'=>asset('uploads/package3.png'),'name_en' => 'Bee Protect', 'name_ar' => 'Bee Protect', 'description_en'=>'Exterior wash - interior- Wax - Tire & wheels polish - interior- floor- engine ','description_ar'=>'غسيل خارجي + تنظيف داخلي + واكس + تلميع الإطارات والرنقات + إزالة بقع الأرضية + مكينة السيارة','price'=>19.5,'category_id' => $category->id])->each(function ($package) {

                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Carpets',
                        'price' => 3.25,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/wheel.png'),
                        'name_en' => 'Tire Polish',
                        'price' => 3.25,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Engine',
                        'price' => 4.5,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Seats',
                        'price' => 7.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Stain Removal',
                        'price' =>  7.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Cargo',
                        'price' => 3.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Polish',
                        'price' => 35.5,
                        'duration' => 180,
                        'package_id' => $package->id
                    ]
                );
            });

            $package4 = factory(Package::class, 1)->create(['image'=>asset('uploads/package3.png'),'name_en' => 'Queen Wash', 'name_ar' => 'Queen Wash', 'description_en'=>'Polish - Exterior wash -  full interior- Wax - Tire & wheels polish - interior- floor- engine','description_ar'=>'
Queen Wash : بوليش ٣ طبقات + غسيل خارجي + واكس+ تنظيف داخلي شامل إزالة البقع بالكامل شامل المقاعد وتلميع الديكورات - تلميع الإطارات والرنقات ','price'=>44.55,'category_id' => $category->id])->each(function ($package) {

                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Carpets',
                        'price' => 3.25,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/wheel.png'),
                        'name_en' => 'Tire Polish',
                        'price' => 3.25,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Engine',
                        'price' => 4.5,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Seats',
                        'price' => 7.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Stain Removal',
                        'price' =>  7.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Cargo',
                        'price' => 3.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Polish',
                        'price' => 35.5,
                        'duration' => 180,
                        'package_id' => $package->id
                    ]
                );
            });
        });


        /**
         * |--------------------------------------------------------------------------
         * | categor 3
         * |--------------------------------------------------------------------------
         */
        factory(Category::class, 1)->create(['name_en' => 'Large Car','image' => asset('uploads/car-small.png')])->each(function ($category) {
            $package1 = factory(Package::class, 1)->create(['image'=>asset('uploads/package1.png'),'name_en' => 'Bee Wash', 'name_ar' => 'Bee Wash', 'description_en'=>'Exterior wash & Interior wash','description_ar'=>'غسيل خارجي + تنظيف داخلي ','category_id' => $category->id,'price'=>5.25])->each(function ($package) {
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Carpets',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/wheel.png'),
                        'name_en' => 'Tire Polish',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Engine',
                        'price' => 3.5,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Seats',
                        'price' => 5.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Stain Removal',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Cargo',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Polish',
                        'price' => 28,
                        'duration' => 180,
                        'package_id' => $package->id
                    ]
                );
            });

            $package2 = factory(Package::class, 1)->create(['image'=>asset('uploads/package2.png'),'name_en' => 'Bee Wax', 'name_ar' => 'Bee Wax', 'description_en'=>'Exterior wash - Interior- Wax - Tire & wheels polish - interior- floor','description_ar'=>'غسيل خارجي+ واكس + تلميع الإطارات والرنقات + تنظيف داخلي + إزالة بقع الأرضية','price'=>12.5,'category_id' => $category->id])->each(function ($package) {

                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Carpets',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/wheel.png'),
                        'name_en' => 'Tire Polish',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Engine',
                        'price' => 3.5,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Seats',
                        'price' => 5.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Stain Removal',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Cargo',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Polish',
                        'price' => 28,
                        'duration' => 180,
                        'package_id' => $package->id
                    ]
                );
            });
            $package3 = factory(Package::class, 1)->create(['image'=>asset('uploads/package3.png'),'name_en' => 'Bee Protect', 'name_ar' => 'Bee Protect', 'description_en'=>'Exterior wash - interior- Wax - Tire & wheels polish - interior- floor- engine ','description_ar'=>'غسيل خارجي + تنظيف داخلي + واكس + تلميع الإطارات والرنقات + إزالة بقع الأرضية + مكينة السيارة','price'=>15.5,'category_id' => $category->id])->each(function ($package) {

                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Carpets',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/wheel.png'),
                        'name_en' => 'Tire Polish',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Engine',
                        'price' => 3.5,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Seats',
                        'price' => 5.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Stain Removal',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Cargo',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Polish',
                        'price' => 28,
                        'duration' => 180,
                        'package_id' => $package->id
                    ]
                );
            });

            $package4 = factory(Package::class, 1)->create(['image'=>asset('uploads/package3.png'),'name_en' => 'Queen Wash', 'name_ar' => 'Queen Wash', 'description_en'=>'Polish - Exterior wash -  full interior- Wax - Tire & wheels polish - interior- floor- engine','description_ar'=>'
Queen Wash : بوليش ٣ طبقات + غسيل خارجي + واكس+ تنظيف داخلي شامل إزالة البقع بالكامل شامل المقاعد وتلميع الديكورات - تلميع الإطارات والرنقات ','price'=>34.75,'category_id' => $category->id])->each(function ($package) {

                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Carpets',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/wheel.png'),
                        'name_en' => 'Tire Polish',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Engine',
                        'price' => 3.5,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Seats',
                        'price' => 5.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Stain Removal',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Cargo',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Polish',
                        'price' => 28,
                        'duration' => 180,
                        'package_id' => $package->id
                    ]
                );
            });
        });

        /**
         * |--------------------------------------------------------------------------
         * | categor 4
         * |--------------------------------------------------------------------------
         */
        factory(Category::class, 1)->create(['name_en' => 'Boat','image' => asset('uploads/car-small.png')])->each(function ($category) {
            $package1 = factory(Package::class, 1)->create(['image'=>asset('uploads/package1.png'),'name_en' => 'Bee Wash', 'name_ar' => 'Bee Wash', 'description_en'=>'Exterior wash & Interior wash','description_ar'=>'غسيل خارجي + تنظيف داخلي ','category_id' => $category->id,'price'=>5.25,'show_quantity'=>1])->each(function ($package) {
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Carpets',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id,
                    ]
                );
            });

        });
        /**
         * |--------------------------------------------------------------------------
         * | categor 5
         * |--------------------------------------------------------------------------
         */
        factory(Category::class, 1)->create(['name_en' => 'Bike','image' => asset('uploads/car-small.png')])->each(function ($category) {
            $package1 = factory(Package::class, 1)->create(['image'=>asset('uploads/package1.png'),'name_en' => 'Bee Wash', 'name_ar' => 'Bee Wash', 'description_en'=>'Exterior wash & Interior wash','description_ar'=>'غسيل خارجي + تنظيف داخلي ','category_id' => $category->id,'price'=>5.25])->each(function ($package) {
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Carpets',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/wheel.png'),
                        'name_en' => 'Tire Polish',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Engine',
                        'price' => 3.5,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Seats',
                        'price' => 5.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Stain Removal',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Cargo',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Polish',
                        'price' => 28,
                        'duration' => 180,
                        'package_id' => $package->id
                    ]
                );
            });

            $package2 = factory(Package::class, 1)->create(['image'=>asset('uploads/package2.png'),'name_en' => 'Bee Wax', 'name_ar' => 'Bee Wax', 'description_en'=>'Exterior wash - Interior- Wax - Tire & wheels polish - interior- floor','description_ar'=>'غسيل خارجي+ واكس + تلميع الإطارات والرنقات + تنظيف داخلي + إزالة بقع الأرضية','price'=>12.5,'category_id' => $category->id])->each(function ($package) {

                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Carpets',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/wheel.png'),
                        'name_en' => 'Tire Polish',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Engine',
                        'price' => 3.5,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Seats',
                        'price' => 5.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Stain Removal',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Cargo',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Polish',
                        'price' => 28,
                        'duration' => 180,
                        'package_id' => $package->id
                    ]
                );
            });
            $package3 = factory(Package::class, 1)->create(['image'=>asset('uploads/package3.png'),'name_en' => 'Bee Protect', 'name_ar' => 'Bee Protect', 'description_en'=>'Exterior wash - interior- Wax - Tire & wheels polish - interior- floor- engine ','description_ar'=>'غسيل خارجي + تنظيف داخلي + واكس + تلميع الإطارات والرنقات + إزالة بقع الأرضية + مكينة السيارة','price'=>15.5,'category_id' => $category->id])->each(function ($package) {

                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Carpets',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/wheel.png'),
                        'name_en' => 'Tire Polish',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Engine',
                        'price' => 3.5,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Seats',
                        'price' => 5.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Stain Removal',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Cargo',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Polish',
                        'price' => 28,
                        'duration' => 180,
                        'package_id' => $package->id
                    ]
                );
            });

            $package4 = factory(Package::class, 1)->create(['image'=>asset('uploads/package3.png'),'name_en' => 'Queen Wash', 'name_ar' => 'Queen Wash', 'description_en'=>'Polish - Exterior wash -  full interior- Wax - Tire & wheels polish - interior- floor- engine','description_ar'=>'
Queen Wash : بوليش ٣ طبقات + غسيل خارجي + واكس+ تنظيف داخلي شامل إزالة البقع بالكامل شامل المقاعد وتلميع الديكورات - تلميع الإطارات والرنقات ','price'=>34.75,'category_id' => $category->id])->each(function ($package) {

                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Carpets',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/wheel.png'),
                        'name_en' => 'Tire Polish',
                        'price' => 2.75,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Car Engine',
                        'price' => 3.5,
                        'duration' => 15,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Seats',
                        'price' => 5.5,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Stain Removal',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Cargo',
                        'price' => 2.75,
                        'duration' => 30,
                        'package_id' => $package->id
                    ]
                );
                factory(Service::class, 1)->create(
                    [
                        'image'=>asset('uploads/seat.png'),
                        'name_en' => 'Polish',
                        'price' => 28,
                        'duration' => 180,
                        'package_id' => $package->id
                    ]
                );
            });
        });



//        factory(Category::class, 1)->create(['name_en' => 'Medium','image' => asset('uploads/car-medium.png')])->each(function ($category) {
//            $package4 = factory(Package::class, 1)->create(['image'=>asset('uploads/package1.png'),'name_en' => 'BeeWash - Package 1', 'category_id' => $category->id])->each(function ($package) {
//                factory(Service::class, 1)->create(['image'=>asset('uploads/seat.png'),'name_en' => 'Seats', 'package_id' => $package->id]);
//                factory(Service::class, 1)->create(['image'=>asset('uploads/wheel.png'),'name_en' => 'Rings', 'package_id' => $package->id]);
//                factory(Service::class, 1)->create(['image'=>asset('uploads/light.png'),'name_en' => 'Lights', 'package_id' => $package->id]);
//            });
//            $package5 = factory(Package::class, 1)->create(['image'=>asset('uploads/package2.png'),'name_en' => 'BeeWash - Package 2', 'category_id' => $category->id])->each(function ($package) {
//                factory(Service::class, 1)->create(['image'=>asset('uploads/seat.png'),'name_en' => 'Seats', 'package_id' => $package->id]);
//                factory(Service::class, 1)->create(['image'=>asset('uploads/wheel.png'),'name_en' => 'Rings', 'package_id' => $package->id]);
//                factory(Service::class, 1)->create(['image'=>asset('uploads/light.png'),'name_en' => 'Lights', 'package_id' => $package->id]);
//            });
//            $package6 = factory(Package::class, 1)->create(['image'=>asset('uploads/package3.png'),'name_en' => 'BeeWash - Package 3', 'category_id' => $category->id])->each(function ($package) {
//                factory(Service::class, 1)->create(['image'=>asset('uploads/seat.png'),'name_en' => 'Seats', 'package_id' => $package->id]);
//                factory(Service::class, 1)->create(['image'=>asset('uploads/wheel.png'),'name_en' => 'Rings', 'package_id' => $package->id]);
//                factory(Service::class, 1)->create(['image'=>asset('uploads/light.png'),'name_en' => 'Lights', 'package_id' => $package->id]);
//            });
//        });




//        factory(Category::class, 1)->create(['name_en' => 'Big','image' => asset('uploads/car-big.png')])->each(function ($category) {
//            $package7 = factory(Package::class, 1)->create(['image'=>asset('uploads/package1.png'),'name_en' => 'BeeWash - Package 1', 'category_id' => $category->id])->each(function ($package) {
//                factory(Service::class, 1)->create(['image'=>asset('uploads/seat.png'),'name_en' => 'Seats', 'package_id' => $package->id]);
//                factory(Service::class, 1)->create(['image'=>asset('uploads/wheel.png'),'name_en' => 'Rings', 'package_id' => $package->id]);
//                factory(Service::class, 1)->create(['image'=>asset('uploads/light.png'),'name_en' => 'Lights', 'package_id' => $package->id]);
//            });
//            $package8 = factory(Package::class, 1)->create(['image'=>asset('uploads/package2.png'),'name_en' => 'BeeWash - Package 2', 'category_id' => $category->id])->each(function ($package) {
//                factory(Service::class, 1)->create(['image'=>asset('uploads/seat.png'),'name_en' => 'Seats', 'package_id' => $package->id]);
//                factory(Service::class, 1)->create(['image'=>asset('uploads/wheel.png'),'name_en' => 'Rings', 'package_id' => $package->id]);
//                factory(Service::class, 1)->create(['image'=>asset('uploads/light.png'),'name_en' => 'Lights', 'package_id' => $package->id]);
//            });
//            $package9 = factory(Package::class, 1)->create(['image'=>asset('uploads/package3.png'),'name_en' => 'BeeWash - Package 3', 'category_id' => $category->id])->each(function ($package) {
//                factory(Service::class, 1)->create(['image'=>asset('uploads/seat.png'),'name_en' => 'Seats', 'package_id' => $package->id]);
//                factory(Service::class, 1)->create(['image'=>asset('uploads/wheel.png'),'name_en' => 'Rings', 'package_id' => $package->id]);
//                factory(Service::class, 1)->create(['image'=>asset('uploads/light.png'),'name_en' => 'Lights', 'package_id' => $package->id]);
//            });
//        });

//        $categories = factory(Category::class,3)->create()->each(function($category) {
//            factory(Package::class,5)->create(['category_id'=>$category->id])->each(function($package) {
//                factory(Service::class,3)->create(['package_id'=>$package->id]);
//            });
//        });

    }
}