<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToTripBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trip_buses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('km_start')->nullable();
            $table->integer('km_end')->nullable();
            $table->integer('km_inside')->nullable();
            $table->float('work_hours')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trip_buses', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropColumn('km_start');
            $table->dropColumn('km_end');
            $table->dropColumn('km_inside');
            $table->dropColumn('work_hours');
            $table->dropTimestamps();
        });
    }
}
