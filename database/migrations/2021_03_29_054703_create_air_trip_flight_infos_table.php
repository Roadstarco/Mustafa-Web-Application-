<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAirTripFlightInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('air_trip_flight_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trip_id');
            $table->string('ident');
            $table->string('aircraft_type');
            $table->string('filed_departure_time');
            $table->string('estimated_arrival_time');
            $table->string('origin');
            $table->string('destination');
            $table->string('origin_name');
            $table->string('origin_city');
            $table->string('destination_name');
            $table->string('destination_city');
            $table->text('api_json_response')->nullable();
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
        Schema::dropIfExists('air_trip_flight_infos');
    }
}
