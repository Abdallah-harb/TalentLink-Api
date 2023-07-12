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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_title',200);
            $table->foreignId('project_type')->references('id')->on('projecttypes')->onDelete('cascade');
            $table->enum('project_nature',['industrial','agricultural','commercial']);
            $table->string('problem',200);
            $table->string('solving',200);
            $table->string('marked_by',200);
            $table->string('target_group',200);
            $table->string('area',200);
            $table->boolean('need_industry');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
