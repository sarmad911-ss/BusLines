<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCalculationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("calculations",function (Blueprint $table){
           $table->dropColumn(['start_waypoint','end_waypoint','waypoints']) ;
           $table->text('start_waypoints')->nullable(true);
           $table->text('end_waypoints')->nullable(true);
           $table->text('start_waypoints_reversed')->nullable(true);
           $table->text('end_waypoints_reversed')->nullable(true);
           $table->text('border_waypoint')->nullable(true);
           $table->text('border_waypoint_reversed')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("calculations",function (Blueprint $table){
            $table->dropColumn(['start_waypoints','end_waypoints','start_waypoints_reversed','end_waypoints_reversed','border_waypoint','border_waypoint_reversed']) ;
            $table->string("start_waypoint")->nullable(true);
            $table->string("end_waypoint")->nullable(true);
            $table->text("waypoints")->nullable(true);
        });
    }
}
