<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Devices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices' , function(Blueprint $table){
            $table->engine = 'InnoDB';
            $table->string('dip');
            $table->string('nname');
            $table->string('nlabel');
            $table->string('sysref');
            $table->string('sysname');
            $table->string('syssoftware');
            $table->string('sysversion');
            $table->string('status');
            $table->primary(['dip','nname']);
            $table->foreign('nname')->references('ip')->on('networks')->onDelete('cascade');
            // $table->foreign('netip')->references('ip')->on('networks')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
