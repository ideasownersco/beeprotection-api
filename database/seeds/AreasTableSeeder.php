<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class AreasTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => null, 'name_ar' => "مدينة الكويت", 'name_en' => "Kuwait City",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => null, 'name_ar' => "حولي", 'name_en' => "Hawally",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => null, 'name_ar' => "فروانية", 'name_en' => "Farwaniya",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => null, 'name_ar' => "أحمدي", 'name_en' => "Ahmadi",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => null, 'name_ar' => "جهراء", 'name_en' => "Jahra",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => null, 'name_ar' => "مبارك الكبير", 'name_en' => "Mubarak Al-Kabir",
        ]);

        //
        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 3, 'name_ar' => "عباسية", 'name_en' => "Abbasiya", 'latitude' => '29.3059631', 'longitude' => '48.0718422'
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 3, 'name_ar' => "عبدالله المبارك", 'name_en' => "Abdullah Al-Mubarak",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "عبدالله السالم", 'name_en' => "Abdullah Al-Salem",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 3, 'name_ar' => "أبرق خيطان", 'name_en' => "Abraq Khaitan",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 6, 'name_ar' => "أبو فطيرة", 'name_en' => "Abu Ftaira",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "ابوحليفة", 'name_en' => "Abu Halifa",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 6, 'name_ar' => "أبو الحصانية", 'name_en' => "Abu Hasaniya",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "عديلية", 'name_en' => "Adailiya",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 6, 'name_ar' => "عدان", 'name_en' => "Adan",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 3, 'name_ar' => "مطار", 'name_en' => "Airport",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "جليعة", 'name_en' => "Julayah",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 6, 'name_ar' => "مسايل", 'name_en' => "Masayel",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 5, 'name_ar' => "نعيم", 'name_en' => "Naeem",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "أحمدي", 'name_en' => "Ahmadi",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 2, 'name_ar' => "بدع", 'name_en' => "Bedae",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => " على صباح السالم", 'name_en' => "Ali Sabah Al-Salem",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 6, 'name_ar' => "قرين", 'name_en' => "Qurain",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 6, 'name_ar' => "قصور", 'name_en' => "Qusour",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 5, 'name_ar' => "أمغرة", 'name_en' => "Amgarah",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 3, 'name_ar' => "أندلس", 'name_en' => "Andalous",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 3, 'name_ar' => "عارضية", 'name_en' => "Ardhiya",
        ]);


        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 3, 'name_ar' => "أشبيليا", 'name_en' => "Ishbeliah",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 2, 'name_ar' => "بيان", 'name_en' => "Bayan",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "بنيد القار", 'name_en' => "Bneid Al-Qar",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "دعية", 'name_en' => "Daiya",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "دسمة", 'name_en' => "Dasma",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "دسمان", 'name_en' => "Dasman",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "ظهر", 'name_en' => "Dhaher",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 3, 'name_ar' => "ضجيج", 'name_en' => "Dhajeej",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "دوحة", 'name_en' => "Doha",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "عقيلة", 'name_en' => "Egaila",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "فهد الاحمد", 'name_en' => "Fahad Al-Ahmed",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "فحيحيل", 'name_en' => "Fahaheel",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "فيحاء", 'name_en' => "Faiha",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 3, 'name_ar' => "فروانية", 'name_en' => "Farwaniya",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 3, 'name_ar' => "فردوس", 'name_en' => "Ferdous",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "فنطاس", 'name_en' => "Fintas",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 6, 'name_ar' => "فنيطيس", 'name_en' => "Fnaitess",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "غرناطة", 'name_en' => "Ghornata",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "هدية", 'name_en' => "Hadiya",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 2, 'name_ar' => "حولي", 'name_en' => "Hawally",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 2, 'name_ar' => "حطين", 'name_en' => "Hitteen",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "جابر الأحمد", 'name_en' => "Jaber Al-Ahmed",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "جابر العلي", 'name_en' => "Jaber Al-Ali",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 2, 'name_ar' => "جابرية", 'name_en' => "Jabriya",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 5, 'name_ar' => "جهراء", 'name_en' => "Jahra",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 3, 'name_ar' => "جليب شيوخ", 'name_en' => "Jeleeb Al-Shuyoukh",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 5, 'name_ar' => "كبد", 'name_en' => "Kabd",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "كيفان", 'name_en' => "Kaifan",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "خيران", 'name_en' => "Khairan",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 3, 'name_ar' => "خيطان", 'name_en' => "Khaitan",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "خالدية", 'name_en' => "Khaldiya",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "مدينة الكويت", 'name_en' => "Kuwait City",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "مقوع", 'name_en' => "Magwa",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "مهبولة", 'name_en' => "Mahboula",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 2, 'name_ar' => "ميدان حولي", 'name_en' => "Maidan Hawally",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "منقف", 'name_en' => "Mangaf",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "منصورية", 'name_en' => "Mansouriya",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 6, 'name_ar' => "مسيلة", 'name_en' => "Messila",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "ميناء عبدالله", 'name_en' => "Mina Abdullah",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "ميناء الاحمدي", 'name_en' => "Mina Al-Ahmadi",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "مرقاب", 'name_en' => "Mirqab",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 2, 'name_ar' => "مشرف", 'name_en' => "Mishrif",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 2, 'name_ar' => "مبارك العبدالله", 'name_en' => "Mubarak Al-Abdullah",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 6, 'name_ar' => "مبارك الكبير", 'name_en' => "Mubarak Al-Kabir",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "نهضة", 'name_en' => "Nahda",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 5, 'name_ar' => "نسيم", 'name_en' => "Nasseem",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "نويصب", 'name_en' => "Nuwaiseeb",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "نزهة", 'name_en' => "Nuzha",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 3, 'name_ar' => "عمرية", 'name_en' => "Omariya",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 5, 'name_ar' => "عيون", 'name_en' => "Oyoun",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "قادسية", 'name_en' => "Qadsiya",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 5, 'name_ar' => "قيروان", 'name_en' => "Qairawan",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 5, 'name_ar' => "قصر", 'name_en' => "Qasr",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "قبلة", 'name_en' => "Qibla",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "قرطبة", 'name_en' => "Qortuba",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 3, 'name_ar' => "رابية", 'name_en' => "Rabiya",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 3, 'name_ar' => "ري", 'name_en' => "Rai",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "روضة", 'name_en' => "Rawda",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 3, 'name_ar' => "رقعي", 'name_en' => "Reggai",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 3, 'name_ar' => "رحاب", 'name_en' => "Rehab",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "رقة", 'name_en' => "Riqqa",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 2, 'name_ar' => "رميثية", 'name_en' => "Rumaithiya",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 5, 'name_ar' => "سعد العبد الله", 'name_en' => "Saad Al-Abdullah",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "صباح الأحمد", 'name_en' => "Sabah Al-Ahmad",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 3, 'name_ar' => "صباح الناصر", 'name_en' => "Sabah Al-Nasser",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 6, 'name_ar' => "صباح السالم", 'name_en' => "Sabah Al-Salem",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "صباحية", 'name_en' => "Sabahiya",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 6, 'name_ar' => "صبحان", 'name_en' => "Sabhan",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 2, 'name_ar' => "سلام", 'name_en' => "Salam",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "صالحية", 'name_en' => "Salhiya",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 2, 'name_ar' => "سالمية", 'name_en' => "Salmiya",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 2, 'name_ar' => "سلوى", 'name_en' => "Salwa",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 2, 'name_ar' => "شعب", 'name_en' => "Shaab",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "شامية", 'name_en' => "Shamiya",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "شرق", 'name_en' => "Sharq",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "ميناء الشعيبة", 'name_en' => "Shuaiba Port",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 2, 'name_ar' => "شهداء", 'name_en' => "Shuhada",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "شويخ", 'name_en' => "Shuwaikh",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 2, 'name_ar' => "صديق", 'name_en' => "Siddiq",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "صليبخات", 'name_en' => "Sulaibikhat",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 5, 'name_ar' => "صليبية", 'name_en' => "Sulaibiya",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "سرة", 'name_en' => "Surra",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 5, 'name_ar' => "تيماء", 'name_en' => "Taima",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 4, 'name_ar' => "وفرة", 'name_en' => "Wafra",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 5, 'name_ar' => "واحة", 'name_en' => "Waha",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 6, 'name_ar' => "وسطي", 'name_en' => "Wista",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 1, 'name_ar' => "يرموك", 'name_en' => "Yarmouk",
        ]);

        DB::table('areas')->insert([
            'country_id' => '1', 'parent_id' => 2, 'name_ar' => "زهراء", 'name_en' => "Zahra",
        ]);
    }
}