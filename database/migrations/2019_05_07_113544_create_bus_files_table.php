<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bus_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bus_id')->unsigned();;
            $table->integer('file_id')->unsigned();;
            $table->timestamps();
        });

        Schema::table('bus_files', function (Blueprint $table) {
            $table->foreign('bus_id')->references('id')->on('buses')->onDelete('cascade');
        });

        Schema::table('bus_files', function (Blueprint $table) {
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bus_files');
    }
}
