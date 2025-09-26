<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Normalize loan_type enum and allow null for reminder days
        // Note: Using raw SQL to avoid doctrine/dbal requirement for change()
        DB::statement("ALTER TABLE seasons MODIFY COLUMN loan_type ENUM('co-funded','complete-loan') NOT NULL DEFAULT 'co-funded'");
        DB::statement("ALTER TABLE seasons MODIFY COLUMN send_reminder_after_days INT NULL");
    }

    public function down(): void
    {
        // Best-effort rollback: set reminder back to NOT NULL DEFAULT 7
        try {
            DB::statement("ALTER TABLE seasons MODIFY COLUMN send_reminder_after_days INT NOT NULL DEFAULT 7");
        } catch (\Throwable $e) {
            // ignore
        }
        // For loan_type, keep as-is to avoid data loss
    }
};
