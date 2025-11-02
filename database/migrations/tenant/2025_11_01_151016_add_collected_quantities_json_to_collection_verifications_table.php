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
        Schema::table('collection_verifications', function (Blueprint $table) {
            $table->json('collected_quantities')->nullable()->after('commodity_photo');
            $table->dropColumn('collected_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_verifications', function (Blueprint $table) {
            $table->decimal('collected_quantity', 10, 2)->nullable()->after('commodity_photo');
            $table->dropColumn('collected_quantities');
        });
    }
};
