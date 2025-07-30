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
        Schema::create('central_seasons', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // UUID colum
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('budget', 12, 2);
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->json('commodities')->nullable(); // e.g., {"maize": "bags", "npk": "bags"}
            $table->date('return_deadline')->nullable();
            $table->decimal('insurance_rate', 5, 2)->default(0); // e.g., 2%
            $table->integer('send_reminder_after_days')->default(7); // days after deadline
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_seasons');
    }
};
