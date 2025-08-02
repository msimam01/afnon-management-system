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
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('budget', 12, 2)->nullable(); // Optional if set globally
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->date('return_deadline')->nullable();
            $table->decimal('insurance_rate', 5, 2)->default(0);
            $table->integer('send_reminder_after_days')->default(7);
            $table->boolean('is_global')->default(false);
            $table->unsignedBigInteger('global_season_id')->nullable(); // Central season ref
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};
