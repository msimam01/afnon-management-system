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
        // First, ensure the global_commodity_categories table exists
        if (!Schema::hasTable('global_commodity_categories')) {
            Schema::create('global_commodity_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }

        // Add the category_id column
        Schema::table('global_commodities', function (Blueprint $table) {
            // Add the new category_id column
            $table->unsignedBigInteger('category_id')->nullable()->after('id');
        });

        // Create a category for existing category strings and update the category_id
        if (Schema::hasColumn('global_commodities', 'category')) {
            $categories = \DB::table('global_commodities')
                ->select('category')
                ->distinct()
                ->pluck('category');
            
            foreach ($categories as $categoryName) {
                // Find or create the category
                $category = \DB::table('global_commodity_categories')
                    ->where('name', $categoryName)
                    ->first();
                
                if (!$category) {
                    $categoryId = \DB::table('global_commodity_categories')->insertGetId([
                        'name' => $categoryName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $categoryId = $category->id;
                }
                
                // Update existing records with the new category_id
                \DB::table('global_commodities')
                    ->where('category', $categoryName)
                    ->update(['category_id' => $categoryId]);
            }
        }

        // Now modify the category_id column to be non-nullable and add foreign key
        Schema::table('global_commodities', function (Blueprint $table) {
            // Make the category_id required
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
            
            // Add the foreign key constraint
            $table->foreign('category_id')
                ->references('id')
                ->on('global_commodity_categories')
                ->onDelete('restrict');
                
            // Drop the old category column if it exists
            if (Schema::hasColumn('global_commodities', 'category')) {
                $table->dropColumn('category');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('global_commodities', function (Blueprint $table) {
            // Add the category column back
            $table->string('category')->after('id');
            
            // Get the category names for each commodity
            $commodities = \DB::table('global_commodities')
                ->join('global_commodity_categories', 'global_commodities.category_id', '=', 'global_commodity_categories.id')
                ->select('global_commodities.id', 'global_commodity_categories.name as category_name')
                ->get();
            
            // Update the category column with the category names
            foreach ($commodities as $commodity) {
                \DB::table('global_commodities')
                    ->where('id', $commodity->id)
                    ->update(['category' => $commodity->category_name]);
            }
            
            // Remove the foreign key constraint
            $table->dropForeign(['category_id']);
            
            // Drop the category_id column
            $table->dropColumn('category_id');
        });
        
        // Drop the categories table if needed (commented out as it might be used by other tables)
        // Schema::dropIfExists('global_commodity_categories');
    }
};
