<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_files', function (Blueprint $table) {
            $table->integer('trip_id');
            $table->integer('file_id');
        });

        Schema::table('trip_files', function (Blueprint $table) {
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('set null');
        });

        Schema::table('trip_files', function (Blueprint $table) {
            $table->foreign('file_id')->references('id')->on('files')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trip_files');
    }
}
