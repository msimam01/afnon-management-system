<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_centers', function (Blueprint $table) {
            // Unique one-to-one per application
            $table->unique('application_id', 'application_centers_application_id_unique');
            // Helpful indexes for queries
            $table->index('collection_center_id', 'application_centers_collection_center_idx');
            $table->index('return_center_id', 'application_centers_return_center_idx');
            $table->index(['collection_center_id', 'return_center_id'], 'application_centers_collection_return_idx');
        });
    }

    public function down(): void
    {
        Schema::table('application_centers', function (Blueprint $table) {
            $table->dropUnique('application_centers_application_id_unique');
            $table->dropIndex('application_centers_collection_center_idx');
            $table->dropIndex('application_centers_return_center_idx');
            $table->dropIndex('application_centers_collection_return_idx');
        });
    }
};
