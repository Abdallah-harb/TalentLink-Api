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
        Schema::create('previous_jobs_for_previous_experiences', function (Blueprint $table) {
            $table->id();
            $table->string('Job_name');
            $table->string('start_year');
            $table->string('end_year');
            $table->string('workplace');
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('previous_jobs_for_previous_experiences');
    }
};
