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
        Schema::create('global_commodity_seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('global_season_id')->constrained()->onDelete('cascade');
            $table->foreignId('global_commodity_id')->constrained()->onDelete('cascade');
            $table->integer('stock');
            $table->unique(
                ['global_season_id', 'global_commodity_id'],
                'gcs_season_commodity_unique'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_commodity_seasons');
    }
};
