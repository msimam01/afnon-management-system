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
        Schema::create('commodities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('type')->nullable();
            $table->string('unit'); // e.g., bags, bundles
            $table->decimal('price_per_unit', 10, 2);
            $table->decimal('quantity_per_hectare', 8, 2); // E.g., 3 units per hectare
            $table->integer('stock');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commodities');
    }
};
