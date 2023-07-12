<?php

namespace Database\Seeders;

use App\Models\Major;
use App\Models\MajorTranslate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MajorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=1;$i<=2;$i++){
            $major = Major::create();
        }


        MajorTranslate::insert([
            [
                "lang"=>"ar",
                "name"=>"علوم",
                "major_id"=>1
            ],
            [
                "lang"=>"ar",
                "name"=>"برمجة ويب",
                "major_id"=>2
            ],

        ]);
    }
}
