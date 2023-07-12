<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\ProvinceTranslate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProfinceDatabaseSeeder extends Seeder
{

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('provinces')->truncate();
        DB::table('province_translates')->truncate();
        Schema::enableForeignKeyConstraints();

       $province = Province::insert([
           [
               'id'=>1,
               "is_active" => 1
           ],
           [
               'id'=>2,
               "is_active" => 1
           ],
           [
               'id'=>3,
               "is_active" => 1
           ],
           [
               'id'=>4,
               "is_active" => 1
           ],
           [
               'id'=>5,
               "is_active" => 1
           ],
           [
               'id'=>6,
               "is_active" => 1
           ],
           [
               'id'=>7,
               "is_active" => 1
           ],
           [
               'id'=>8,
               "is_active" => 1
           ],
           [
               'id'=>9,
               "is_active" => 1
           ],
           [
               'id'=>10,
               "is_active" => 1
           ],
           [
               'id'=>11,
               "is_active" => 1
           ],
           [
               'id'=>12,
               "is_active" => 1
           ],
           [
               'id'=>13,
               "is_active" => 1
           ],
           [
               'id'=>14,
               "is_active" => 1
           ],
           [
               'id'=>15,
               "is_active" => 1
           ],
           [
               'id'=>16,
               "is_active" => 1
           ],
           [
               'id'=>17,
               "is_active" => 1
           ],
           [
               'id'=>18,
               "is_active" => 1
           ],
       ]);

       $province_translate = ProvinceTranslate::insert([
               [
                   "lang" => "ar",
                   "name" => "الأنبار",
                   "province_id" =>1,
               ],
               [
                   "lang" => "ar",
                   "name" => "بابل",
                   "province_id" => 2,
               ],
               [
                   "lang" => "ar",
                   "name" => "بغداد",
                   "province_id" => 3,
               ],
               [
                   "lang" => "ar",
                   "name" => "البصرة",
                   "province_id" =>4,
               ],
               [
                   "lang" => "ar",
                   "name" => "ذي قار",
                   "province_id" => 5,
               ],
               [
                   "lang" => "ar",
                   "name" => "ديالى",
                   "province_id" => 6,
               ],
               [
                   "lang" => "ar",
                   "name" => "ديالى",
                   "province_id" =>7,
               ],
               [
                   "lang" => "ar",
                   "name" => "أربيل",
                   "province_id" =>8,
               ],
               [
                   "lang" => "ar",
                   "name" => "كربلاء",
                   "province_id" => 9,
               ],
               [
                   "lang" => "ar",
                   "name" => "كركوك",
                   "province_id" => 10,
               ],
               [
                   "lang" => "ar",
                   "name" => "ميسان",
                   "province_id" => 11,
               ],
               [
               "lang" => "ar",
               "name" => "المثنى",
               "province_id" => 12,
               ],
               [
                   "lang" => "ar",
                   "name" => "النجف",
                   "province_id" => 13,
               ],
               [
                   "lang" => "ar",
                   "name" => "نينوى",
                   "province_id" => 14,
               ],
               [
                   "lang" => "ar",
                   "name" => "القادسية",
                   "province_id" => 15,
               ],
               [
                   "lang" => "ar",
                   "name" => "صلاح الدين",
                   "province_id" => 16,
               ],
               [
                   "lang" => "ar",
                   "name" => "السليمانية",
                   "province_id" => 17,
               ],
               [
                   "lang" => "ar",
                   "name" => "واسط",
                   "province_id" => 18,
               ]
      ]);
    }
}
