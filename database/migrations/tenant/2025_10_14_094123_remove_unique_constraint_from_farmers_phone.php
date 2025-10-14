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
        Schema::table('farmers', function (Blueprint $table) {
            // Remove unique constraints
            $table->dropUnique('farmers_phone_unique');
            $table->dropUnique('farmers_nin_unique');
            $table->dropUnique('farmers_bvn_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farmers', function (Blueprint $table) {
            // Re-add unique constraints if rolling back
            $table->string('phone')->unique()->change();
            $table->string('nin')->unique()->change();
            $table->string('bvn')->unique()->change();
        });
    }
};
