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
        // Add performance indexes for applications table
        Schema::table('applications', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'idx_applications_status_created');
            $table->index(['season_id', 'status'], 'idx_applications_season_status');
            $table->index(['farmer_id', 'created_at'], 'idx_applications_farmer_created');
        });

        // Add performance indexes for farmers table
        Schema::table('farmers', function (Blueprint $table) {
            $table->index(['full_name'], 'idx_farmers_name');
            $table->index(['phone'], 'idx_farmers_phone');
            $table->index(['registration_number'], 'idx_farmers_registration');
        });

        // Add performance indexes for seasons table
        Schema::table('seasons', function (Blueprint $table) {
            $table->index(['status'], 'idx_seasons_status');
            $table->index(['name'], 'idx_seasons_name');
        });

        // Add performance indexes for centers table
        Schema::table('centers', function (Blueprint $table) {
            $table->index(['type'], 'idx_centers_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('idx_applications_status_created');
            $table->dropIndex('idx_applications_season_status');
            $table->dropIndex('idx_applications_farmer_created');
        });

        Schema::table('farmers', function (Blueprint $table) {
            $table->dropIndex('idx_farmers_name');
            $table->dropIndex('idx_farmers_phone');
            $table->dropIndex('idx_farmers_registration');
        });

        Schema::table('seasons', function (Blueprint $table) {
            $table->dropIndex('idx_seasons_status');
            $table->dropIndex('idx_seasons_name');
        });

        Schema::table('centers', function (Blueprint $table) {
            $table->dropIndex('idx_centers_type');
        });
    }
};
