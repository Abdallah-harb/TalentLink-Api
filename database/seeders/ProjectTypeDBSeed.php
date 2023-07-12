<?php

namespace Database\Seeders;

use App\Models\Projecttype;
use App\Models\ProjecttypeTranslate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProjectTypeDBSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('projecttypes')->truncate();
        DB::table('projecttype_translates')->truncate();
        Schema::enableForeignKeyConstraints();
        for($i=1;$i<=3;$i++){
            Projecttype::create();
        }
        ProjecttypeTranslate::insert([
           [
                "lang" => "ar",
                "name" => "إستراتيجي",
                "projecttype_id" => 1
           ],
           [
               "lang" => "ar",
               "name" => "التشغيل",
               "projecttype_id" => 2
           ],
           [
               "lang" => "ar",
               "name" => "امتثال",
               "projecttype_id" => 3
           ]
        ]);
    }
}
