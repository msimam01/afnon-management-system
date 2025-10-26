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
        Schema::table('global_commodity_market_prices', function (Blueprint $table) {
            $table->date('effective_date')->after('current_price');
            $table->text('notes')->nullable()->after('effective_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('global_commodity_market_prices', function (Blueprint $table) {
            $table->dropColumn(['effective_date', 'notes']);
        });
    }
};
