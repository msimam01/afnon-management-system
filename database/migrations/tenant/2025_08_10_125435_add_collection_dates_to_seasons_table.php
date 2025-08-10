<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('seasons', function (Blueprint $table) {
            $table->date('collection_start_date')->nullable()->after('name');
            $table->date('collection_end_date')->nullable()->after('collection_start_date');
        });
    }

    public function down()
    {
        Schema::table('seasons', function (Blueprint $table) {
            $table->dropColumn(['collection_start_date', 'collection_end_date']);
        });
    }
};
