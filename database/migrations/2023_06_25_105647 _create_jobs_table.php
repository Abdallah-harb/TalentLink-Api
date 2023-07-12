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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_title',100);
            $table->enum('job_type',['fullTime','partTime','remotely']);
            $table->foreignId('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreignId('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->enum('job_level',['advanced','intermediate','freshGraduated','highExperience','manager']);
            $table->string('start_year');
            $table->string('end_year');
            $table->string('start_salary')->nullable();
            $table->string('end_salary')->nullable();
            $table->boolean('agreement_with_employee')->nullable();
            $table->string('start_job_data');
            $table->text('job_description');
            $table->text('job_requirement');
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
        Schema::dropIfExists('jobs');
    }
};
