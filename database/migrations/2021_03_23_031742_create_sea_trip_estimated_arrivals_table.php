<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeaTripEstimatedArrivalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sea_trip_estimated_arrivals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trip_id');
            $table->integer('ship_id');
            $table->integer('mmsi');
            $table->integer('imo');
            $table->integer('last_port_id');
            $table->string('last_port');
            $table->string('last_port_unlocode');
            $table->date('last_port_time');
            $table->string('next_port_name');
            $table->string('next_port_unlocode');
            $table->date('eta_calc');
            $table->text('api_json_response');
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
        Schema::dropIfExists('sea_trip_estimated_arrivals');
    }
}
