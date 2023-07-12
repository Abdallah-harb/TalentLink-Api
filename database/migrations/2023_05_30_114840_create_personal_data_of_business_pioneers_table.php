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
        Schema::create('personal_data_of_business_pioneers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('title');
            $table->foreignId('types_education_id')->references('id')->on('types_education')->cascadeOnDelete();
            $table->foreignId('major_id')->references('id')->on('majors')->cascadeOnDelete();
            $table->foreignId('province_id')->references('id')->on('provinces')->cascadeOnDelete();
            $table->foreignId('city_id')->references('id')->on('cities')->cascadeOnDelete();
            $table->foreignId('nationality_id')->references('id')->on('nationalities')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->references('id')->on('personal_data_of_business_pioneers')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_data_of_business_pioneers');
    }
};
