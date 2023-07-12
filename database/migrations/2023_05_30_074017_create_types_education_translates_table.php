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
        Schema::create('types_education_translates', function (Blueprint $table) {
            $table->id();
            $table->string('lang');
            $table->string('name');
            $table->foreignId('types_education_id')->references('id')->on('types_education')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('types_education_translates');
    }
};
