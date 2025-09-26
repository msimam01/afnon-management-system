<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monetary_returns', function (Blueprint $table) {
            if (!Schema::hasColumn('monetary_returns', 'tx_ref')) {
                $table->string('tx_ref')->nullable()->index();
            }
            if (!Schema::hasColumn('monetary_returns', 'calculation_method')) {
                $table->string('calculation_method')->nullable();
            }
            if (!Schema::hasColumn('monetary_returns', 'calculation_details')) {
                $table->text('calculation_details')->nullable();
            }
            if (!Schema::hasColumn('monetary_returns', 'payment_provider')) {
                $table->string('payment_provider', 50)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('monetary_returns', function (Blueprint $table) {
            if (Schema::hasColumn('monetary_returns', 'payment_provider')) {
                $table->dropColumn('payment_provider');
            }
            if (Schema::hasColumn('monetary_returns', 'calculation_details')) {
                $table->dropColumn('calculation_details');
            }
            if (Schema::hasColumn('monetary_returns', 'calculation_method')) {
                $table->dropColumn('calculation_method');
            }
            // Keep tx_ref column in place to avoid losing references
        });
    }
};
