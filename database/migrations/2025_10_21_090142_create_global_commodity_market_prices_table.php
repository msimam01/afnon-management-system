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
        Schema::create('global_commodity_market_prices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // UUID colum
            $table->foreignId('global_commodity_id')->constrained();
            $table->foreignId('global_season_id')->nullable()->constrained();
            $table->decimal('current_price', 12, 2);
            // $table->unique(['global_commodity_id', 'global_season_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_commodity_market_prices');
    }
};
