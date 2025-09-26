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
        Schema::table('seasons', function (Blueprint $table) {
            if (!Schema::hasColumn('seasons', 'loan_type')) {
                $table->enum('loan_type', ['co-funded', 'complete-loan'])->default('co-funded')->after('type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seasons', function (Blueprint $table) {
            if (Schema::hasColumn('seasons', 'loan_type')) {
                $table->dropColumn('loan_type');
            }
        });
    }
};
