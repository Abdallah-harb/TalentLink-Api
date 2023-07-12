<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('personal_data_of_job_seekers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('title');
            $table->foreignId('province_id')->references('id')->on('provinces')->cascadeOnDelete();
            $table->foreignId('city_id')->references('id')->on('cities')->cascadeOnDelete();
            $table->foreignId('nationality_id')->references('id')->on('nationalities')->cascadeOnDelete();
            $table->enum("gender",["male","female"]);
            $table->date("date_of_birth");
            $table->enum("marital_status" ,[
                "single",
                "married",
                "absolute",
                "widower",
            ]);
            $table->text('description');
            $table->string('the_biography_file')->nullable();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_data_of_job_seekers');
    }
};
