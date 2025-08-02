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
        Schema::create('quota_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained('central_seasons');
            $table->string('tenant'); // E.g., "kano"
            $table->foreignId('commodity_id')->constrained('central_commodities');
            $table->integer('allocated_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quota_allocations');
    }
};
