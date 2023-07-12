<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Nationality;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{



    public function run(): void
    {

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(ProfinceDatabaseSeeder::class);
        $this->call(CityDatabaseSeeder::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(MajorTableSeeder::class);
        $this->call(TypesEducationTableSeeder::class);
        $this->call(NationalityTableSeeder::class);
        $this->call(ProjectTypeDBSeed::class);

    }
}
