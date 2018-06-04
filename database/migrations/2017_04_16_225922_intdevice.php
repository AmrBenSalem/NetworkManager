<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Intdevice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intdevice' , function(Blueprint $table){
            $table->engine = 'InnoDB';
            $table->integer('id');
            $table->string('ip');
            $table->string('description');
            $table->string('intip');
            $table->string('type');
            $table->string('mtu');
            $table->string('speed');
            $table->string('adminstatus');
            $table->string('operstatus');
            $table->foreign('ip')->references('dip')->on('devices')->onDelete('cascade');
            $table->primary(['id','ip']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intdevice');
    }
}
