<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripSegmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_segments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('from')->nullable(true);
            $table->string('to')->nullable(true);
            $table->string('duration')->nullable(true);
            $table->boolean('inside')->nullable(true);
            $table->integer('trip_id')->nullable(true);
            $table->timestamps();
        });

        Schema::table('trip_segments', function (Blueprint $table) {
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trip_segments');
    }
}
