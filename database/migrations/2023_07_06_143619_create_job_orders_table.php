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
        Schema::create('job_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('job_title',100);
            $table->enum('job_type',['fullTime','partTime','remotely']);
            $table->foreignId('major_id')->references('id')->on('majors')->onDelete('cascade');
            $table->foreignId('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreignId('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->enum('job_level',['advanced','intermediate','freshGraduated','highExperience','manager']);
            $table->string('start_year');
            $table->string('end_year');
            $table->string('start_salary')->nullable();
            $table->string('end_salary')->nullable();
            $table->boolean('agreement_with_employee')->nullable();
            $table->text('technical_words');
            $table->text('personal_skills');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_orders');
    }
};
