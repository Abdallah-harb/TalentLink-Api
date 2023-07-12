<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\LanguageTranslate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {

        Schema::disableForeignKeyConstraints();
        DB::table('languages')->truncate();
        DB::table('language_translates')->truncate();
        Schema::enableForeignKeyConstraints();


        $lang = Language::insert([
            [
                "id" => 1,
                "is_active" => 1
            ],
            [
                "id" => 2,
                "is_active" => 1
            ],
            [
                "id" => 3,
                "is_active" => 1
            ],
            [
                "id" => 4,
                "is_active" => 1
            ]
        ]);

        LanguageTranslate::insert([
            [
                "lang"=>"ar",
                "name"=>"عربي",
                "language_id"=>1
            ],
            [
                "lang"=>"en",
                "name"=>"English",
                "language_id"=>2
            ],
            [
                "lang"=>"fn",
                "name"=>"french",
                "language_id"=>3
            ],
            [
                "lang"=>"qr",
                "name"=>"qurdey",
                "language_id"=>4
            ],


        ]);
    }
}
