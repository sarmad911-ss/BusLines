<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("trips",function(Blueprint $table){
           $table->string("type")->nullable(true);
        });
        Schema::table("waypoints",function (Blueprint $table){
            $table->string("type")->nullable(true);
            $table->string("comment")->nullable(true);
            $table->integer("date")->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("trips",function(Blueprint $table){
            $table->dropColumn(["type"]);
        });
        Schema::table("waypoints",function (Blueprint $table){
            $table->dropColumn(["type","comment", "date"]);
        });

    }
}
