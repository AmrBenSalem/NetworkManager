<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Networks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('networks' , function(Blueprint $table){
            $table->engine = 'InnoDB';
            $table->string('ip');
            $table->string('nname');
            $table->string('status');
            $table->string('profile');
            $table->primary('ip');
            $table->foreign('profile')->references('label')->on('profiles')->onDelete('cascade');
        });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('networks');
    }
}
