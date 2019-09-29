<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalculationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calculations', function (Blueprint $table) {
            $table->increments('id');
            $table->string("start_waypoint")->nullable(true);
            $table->string("end_waypoint")->nullable(true);
            $table->text("waypoints")->nullable(true);
            $table->float("inside_distance")->nullable(true);
            $table->float("outside_distance")->nullable(true);
            $table->float("profit_percent")->nullable(true);
            $table->float("km_inside")->nullable(true);
            $table->boolean("km_inside_with_nds")->default(false);
            $table->float("other_costs")->nullable(true);
            $table->integer("days")->nullable(true);
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
        Schema::dropIfExists('calculations');
    }
}
