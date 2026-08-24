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
        Schema::create('commodity_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained()->onDelete('cascade');
            $table->foreignId('application_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('commodity_id')->constrained();
            $table->foreignId('agent_id')->nullable()->constrained();
            $table->foreignId('center_id')->nullable()->constrained();
            $table->enum('return_mode', ['money', 'commodity'])->default('commodity');
            $table->decimal('expected_return_value', 12, 2)->nullable();
            $table->integer('returned_quantity');
            $table->string('image_path')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commodity_returns');
    }
};
