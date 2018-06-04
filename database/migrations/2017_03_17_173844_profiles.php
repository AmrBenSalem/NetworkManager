<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Profiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles' , function(Blueprint $table){
            $table->engine = 'InnoDB';
            $table->string('label')->primary();
            $table->Integer('pingnombre');
            $table->string('pingtime');
            $table->Integer('scannombre');
            $table->string('scantime'); 
            $table->Integer('backupnombre');
            $table->string('backuptime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
