<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For collection_verifications table
        Schema::table('collection_verifications', function (Blueprint $table) {
            if (!Schema::hasColumn('collection_verifications', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('collection_verifications', 'verification_notes')) {
                $table->text('verification_notes')->nullable();
            }
        });

        // For return_verifications table
        Schema::table('return_verifications', function (Blueprint $table) {
            if (!Schema::hasColumn('return_verifications', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('return_verifications', 'verification_notes')) {
                $table->text('verification_notes')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop columns from collection_verifications if they exist
        Schema::table('collection_verifications', function (Blueprint $table) {
            if (Schema::hasColumn('collection_verifications', 'approved_by')) {
                $table->dropForeign(['approved_by']);
                $table->dropColumn('approved_by');
            }
            if (Schema::hasColumn('collection_verifications', 'verification_notes')) {
                $table->dropColumn('verification_notes');
            }
        });

        // Drop columns from return_verifications if they exist
        Schema::table('return_verifications', function (Blueprint $table) {
            if (Schema::hasColumn('return_verifications', 'approved_by')) {
                $table->dropForeign(['approved_by']);
                $table->dropColumn('approved_by');
            }
            if (Schema::hasColumn('return_verifications', 'verification_notes')) {
                $table->dropColumn('verification_notes');
            }
        });
    }
};
