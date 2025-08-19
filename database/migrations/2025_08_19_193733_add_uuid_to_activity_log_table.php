<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id')->index();
        });

        // Generate UUIDs for existing records
        DB::table('activity_log')->whereNull('uuid')->update([
            'uuid' => DB::raw('(SELECT UUID())')
        ]);

        // Make UUID column non-nullable
        Schema::table('activity_log', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
