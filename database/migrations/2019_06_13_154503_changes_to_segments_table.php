<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangesToSegmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trip_segments', function (Blueprint $table) {
            $table->renameColumn('address', 'location');
        });

        Schema::rename('trip_segments', 'waypoints');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('waypoints', 'trip_segments');

        Schema::table('trip_segments', function (Blueprint $table) {
            $table->renameColumn('address', 'location');
        });
    }
}
