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
        Schema::table('monetary_returns', function (Blueprint $table) {
            $table->string('calculation_method')->nullable()->after('status');
            $table->json('calculation_details')->nullable()->after('calculation_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monetary_returns', function (Blueprint $table) {
            $table->dropColumn(['calculation_method', 'calculation_details']);
        });
    }
};
