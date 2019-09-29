<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("users",function (Blueprint $table){
           $table->string("representative_last_name")->nullable(true);
           $table->string("code")->nullable(true);
           $table->string("city")->nullable(true);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("users",function (Blueprint $table){
            $table->dropColumn(["representative_last_name","code","city"]);
        });
    }
}
