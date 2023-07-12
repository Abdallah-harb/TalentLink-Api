<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_jobs', function (Blueprint $table) {
            $table->boolean('recommendation')->default(0)->after('cv');
        });
    }


    public function down(): void
    {
        Schema::table('user_jobs', function (Blueprint $table) {
            $table->dropColumn('recommendation');
        });
    }
};
