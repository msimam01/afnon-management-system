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
        Schema::table('return_verifications', function (Blueprint $table) {
            $table->decimal('expected_quantity', 10, 2)->after('returned_commodity_photo');
            $table->decimal('returned_quantity', 10, 2)->after('expected_quantity');
            $table->decimal('variance', 10, 2)->after('returned_quantity');
            $table->text('shortfall_reason')->nullable()->after('variance');
            $table->boolean('partial_return')->default(false)->after('shortfall_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('return_verifications', function (Blueprint $table) {
            $table->dropColumn(['expected_quantity', 'returned_quantity', 'variance', 'shortfall_reason', 'partial_return']);
        });
    }
};
