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
        Schema::create('commodity_allocations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // UUID colum
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->string('commodity_name');
            $table->decimal('qty_per_hectare', 10, 2)->default(0);
            $table->decimal('allocated_quantity', 10, 2)->default(0);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('total_value', 15, 2)->default(0);
            $table->string('status')->default('pending'); // pending, collected, returned
            $table->string('collection_proof')->nullable(); // path to farmer & commodity picture
            $table->timestamp('collected_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commodity_allocations');
    }
};
