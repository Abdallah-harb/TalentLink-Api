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
        Schema::create('basic_education_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('types_education_id')->references('id')->on('types_education')->cascadeOnDelete();
            $table->string('graduation_year');
            $table->string('college_or_institute_name');
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basic_education_data');
    }
};
