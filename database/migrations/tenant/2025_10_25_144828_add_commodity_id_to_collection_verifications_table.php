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
        Schema::table('collection_verifications', function (Blueprint $table) {
            $table->unsignedBigInteger('commodity_id')->nullable()->after('agent_id');
            $table->foreign('commodity_id')->references('id')->on('commodities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_verifications', function (Blueprint $table) {
            $table->dropForeign(['commodity_id']);
            $table->dropColumn('commodity_id');
        });
    }
};
