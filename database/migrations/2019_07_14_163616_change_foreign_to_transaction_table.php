<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeForeignToTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['file_id']);
        });

        Schema::table('transactions', function (Blueprint $table) {
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
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['file_id']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
        });
    }
}
