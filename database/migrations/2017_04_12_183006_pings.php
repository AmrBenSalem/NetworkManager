<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Pings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pings' , function(Blueprint $table){
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('ip');
            $table->string('nname');
            $table->dateTime('created_at');
            $table->string('status');
            $table->foreign('ip')->references('dip')->on('devices')->onDelete('cascade');
            $table->foreign('nname')->references('nname')->on('devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pings');
    }
}
