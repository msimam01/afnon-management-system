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
        Schema::table('tenants', function (Blueprint $table) {
            // Only add columns that don't exist
            if (!Schema::hasColumn('tenants', 'activated_at')) {
                $table->timestamp('activated_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('tenants', 'deactivated_at')) {
                $table->timestamp('deactivated_at')->nullable()->after('activated_at');
            }
            if (!Schema::hasColumn('tenants', 'deactivation_reason')) {
                $table->text('deactivation_reason')->nullable()->after('deactivated_at');
            }
        });

        // Update existing status column to include all enum values
        DB::statement("ALTER TABLE tenants MODIFY COLUMN status ENUM('pending', 'active', 'inactive', 'suspended', 'failed') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['activated_at', 'deactivated_at', 'deactivation_reason']);
        });
    }
};
