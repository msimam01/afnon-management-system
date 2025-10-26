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
        Schema::create('allocations', function (Blueprint $table) {
            $table->id();
    $table->unsignedBigInteger('season_id');
    $table->unsignedBigInteger('commodity_id');
    $table->decimal('allocated_stock', 10, 2);
    $table->timestamps();
    $table->foreign('season_id')->references('id')->on('seasons')->onDelete('cascade');
    $table->foreign('commodity_id')->references('id')->on('commodities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allocations');
    }
};
