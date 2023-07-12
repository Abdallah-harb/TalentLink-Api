<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\CityTranslate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CityDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('cities')->truncate();
        DB::table('city_translates')->truncate();
        Schema::enableForeignKeyConstraints();

          $city =  City::insert([
              [
                  "id" => 1,
                  "is_active" => 1,
                  "province_id" => 1,
              ],
                [
                    "id" => 2,
                    "is_active" => 1,
                    "province_id" => 1,
                ],


            ]);

            CityTranslate::insert([
               [
                   "lang"    => "ar",
                   "name"    => "الرصافة",
                   "city_id" => 1,
               ],
                [
                    "lang"  => "ar",
                    "name"   => "الكرخ",
                    "city_id" => 2,
                ],
            ]);

    }
}
