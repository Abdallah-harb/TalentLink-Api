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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_title',100);
            $table->enum('course_type',['Practical','theoretical','PracticalAndTheoretical']);
            $table->foreignId('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreignId('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->enum('course_level',['advanced','intermediate','freshGraduated','highExperience','manager']);
            $table->string('start_year');
            $table->string('end_year');
            $table->decimal('course_cost',10,2);
            $table->text('course_description');
            $table->string('professor');
            $table->string('duration',100);
            $table->text('technical_words');
            $table->text('personal_skills');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
