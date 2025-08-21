<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_centers', function (Blueprint $table) {
            // Unique one-to-one per application (per-tenant)
            $table->unique('application_id', 'tenant_application_centers_application_id_unique');
            $table->index('collection_center_id', 'tenant_application_centers_collection_center_idx');
            $table->index('return_center_id', 'tenant_application_centers_return_center_idx');
            $table->index(['collection_center_id', 'return_center_id'], 'tenant_application_centers_collection_return_idx');
        });
    }

    public function down(): void
    {
        Schema::table('application_centers', function (Blueprint $table) {
            $table->dropUnique('tenant_application_centers_application_id_unique');
            $table->dropIndex('tenant_application_centers_collection_center_idx');
            $table->dropIndex('tenant_application_centers_return_center_idx');
            $table->dropIndex('tenant_application_centers_collection_return_idx');
        });
    }
};
