<?php

namespace Database\Seeders;

use App\Models\TypesEducation;
use App\Models\TypesEducationTranslate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypesEducationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $type = TypesEducation::create();

        TypesEducationTranslate::create([
            "lang"=>"ar",
            "name"=>"حاسبات ومعلومات",
            "types_education_id"=>$type->id
        ]);
    }
}
