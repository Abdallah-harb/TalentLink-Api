<?php

namespace Database\Seeders;

use App\Models\Nationality;
use App\Models\NationalityTranslate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NationalityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nationality = Nationality::create();

        NationalityTranslate::create([
            "lang" => "ar",
            "name" => "مصرى",
            "nationality_id" => $nationality->id
        ]);
    }
}
