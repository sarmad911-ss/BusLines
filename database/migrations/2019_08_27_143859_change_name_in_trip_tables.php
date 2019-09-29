<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNameInTripTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trip_services', function (Blueprint $table) {
            $table->text('name')->change();
        });

        Schema::table('trip_conditions', function (Blueprint $table) {
            $table->text('name')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trip_services', function (Blueprint $table) {
            $table->string('name')->change();
        });

        Schema::table('trip_conditions', function (Blueprint $table) {
            $table->string('name')->change();
        });
    }
}
