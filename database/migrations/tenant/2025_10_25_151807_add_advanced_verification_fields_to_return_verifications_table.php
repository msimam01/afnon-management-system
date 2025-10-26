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
            $table->decimal('location_lat', 10, 7)->nullable()->after('partial_return');
            $table->decimal('location_lng', 10, 7)->nullable()->after('location_lat');
            $table->text('signature')->nullable()->after('location_lng');
            $table->boolean('fraud_flag')->default(false)->after('signature');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('return_verifications', function (Blueprint $table) {
            $table->dropColumn(['location_lat', 'location_lng', 'signature', 'fraud_flag']);
        });
    }
};
