<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SnmpStatistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('snmp_statistics' , function(Blueprint $table){
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->dateTime('stat_time');
            $table->bigInteger('total_received');
            $table->bigInteger('total_translated');
            $table->bigInteger('total_ignored');
            $table->bigInteger('total_unknown');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('snmp_statistics');
    }
}
