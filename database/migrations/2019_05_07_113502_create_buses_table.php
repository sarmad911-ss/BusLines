<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->unsigned();
            $table->string('model')->nullable();
            $table->string('plate_number')->nullable();
            $table->integer('seats_quantity')->nullable();
            $table->integer('owner_id')->unsigned()->nullable();
            $table->string('vin')->nullable();
            $table->string('misc')->nullable();
            $table->float('mileage_start')->default(0);
            $table->integer('euro_norm_id')->unsigned()->nullable();
            $table->integer('release_date')->nullable();
            $table->timestamps();
        });

        Schema::table('buses', function (Blueprint $table) {
            $table->foreign('type_id')->references('id')->on('bus_types')->onDelete('cascade');
        });

        Schema::table('buses', function (Blueprint $table) {
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('buses', function (Blueprint $table) {
            $table->foreign('euro_norm_id')->references('id')->on('euro_norms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buses');
    }
}
