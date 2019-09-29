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
        	$table->integer("photo_id")->nullable(true)->unsigned();
        	$table->integer("dob")->nullable(true);
        	$table->text("misc")->nullable(true);

        	$table->foreign("photo_id")->references("id")->on("files")->onDelete("set null");
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
	    	$table->dropColumn(['photo_id','dob','misc']);
	    });
    }
}
