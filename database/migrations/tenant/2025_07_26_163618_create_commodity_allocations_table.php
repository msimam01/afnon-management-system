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
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->foreignId('commodity_id')->constrained()->onDelete('cascade');
            $table->foreignId('center_id')->nullable()->constrained();
            $table->integer('allocated_quantity');
            $table->decimal('unit_price', 10, 2)->nullable(); // For tracking
            $table->decimal('value', 12, 2)->nullable(); // quantity * price
            $table->enum('status', ['pending', 'collected'])->default('pending');
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
