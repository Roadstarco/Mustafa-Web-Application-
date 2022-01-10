<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAirportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('airport_id');
            $table->string('type');
            $table->string('latitude_deg');
            $table->string('longitude_deg');
            $table->string('elevation_ft');
            $table->string('iso_country');
            $table->string('iso_region');
            $table->string('municipality');
            $table->string('ident');
            $table->string('ident');

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
        Schema::dropIfExists('airports');
    }
}
