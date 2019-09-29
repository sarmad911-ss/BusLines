<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCalculationsFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("calculations",function (Blueprint $table){
           $table->string("type")->nullable(true);
           $table->string("stage")->nullable(true);
           $table->float("cost")->nullable(true);
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
            $table->dropColumn("type","stage","cost");
        });
    }
}
