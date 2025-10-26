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
        // Simply drop the old unique constraint to allow multiple commodities
        // The foreign keys will remain intact
        $indexes = DB::select("SHOW INDEXES FROM global_tenant_allocations WHERE Key_name = 'tenant_season_unique'");

        if (!empty($indexes)) {
            Schema::table('global_tenant_allocations', function (Blueprint $table) {
                $table->dropUnique('tenant_season_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // In the reverse migration, we should also check if the index exists before dropping
        $indexes = DB::select("SHOW INDEXES FROM global_tenant_allocations WHERE Key_name = 'tenant_season_unique'");

        if (!empty($indexes)) {
            Schema::table('global_tenant_allocations', function (Blueprint $table) {
                $table->dropUnique('tenant_season_unique');
            });
        }
    }
};
