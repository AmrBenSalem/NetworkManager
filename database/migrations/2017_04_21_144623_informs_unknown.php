<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InformsUnknown extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informs_unknown' , function(Blueprint $table){
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('trapoid',100);
            $table->string('enterprise',100);
            $table->string('community',20);
            $table->string('hostname',100);
            $table->string('agentip',16);
            $table->string('uptime',20);
            $table->dateTime('traptime');
            $table->string('formatline',400);
            $table->foreign('hostname')->references('dip')->on('devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('informs_unknown');
    }
}
