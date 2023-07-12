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
        Schema::create('companies_and_institutes_data', function (Blueprint $table) {
            $table->id();
            $table->date('date_establishment');
            $table->string('registration_number');
            $table->string('link');
            $table->string('logo');
            $table->foreignId('major_id')->references('id')->on('majors')->cascadeOnDelete();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies_and_anstitutes_data');
    }
};
