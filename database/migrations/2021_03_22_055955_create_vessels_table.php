<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVesselsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vessels', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('imo');
            $table->string('name');
            $table->string('flag');
            $table->string('type');
            $table->integer('built')->nullable();
            $table->string('builder')->nullable();
            $table->string('manager')->nullable();
            $table->string('owner')->nullable();
            $table->float('length')->nullable();
            $table->float('beam')->nullable();
            $table->float('max_draught')->nullable();
            $table->integer('gt')->nullable();
            $table->integer('nt')->nullable();
            $table->integer('dwt')->nullable();
            $table->integer('teu')->nullable();
            $table->integer('crude')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vessels');
    }
}
