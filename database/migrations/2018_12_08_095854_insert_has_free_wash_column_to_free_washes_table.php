<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertHasFreeWashColumnToFreeWashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('free_washes', function (Blueprint $table) {
            $table->boolean('has_free_wash')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('free_washes', function (Blueprint $table) {
            $table->dropColumn('has_free_wash');
        });
    }
}
