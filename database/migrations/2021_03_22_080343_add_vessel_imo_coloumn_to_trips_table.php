<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVesselImoColoumnToTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->integer('vessel_id')->nullable()->after('service_type');
            $table->integer('vessel_imo')->nullable()->after('vessel_id');
            $table->string('vessel_name')->nullable()->after('vessel_imo');
            $table->string('source_port_id')->nullable()->after('vessel_name');
            $table->string('destination_port_id')->nullable()->after('source_port_locode');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn(['vessel_imo','vessel_name', 'source_port_locode','destination_port_locode']);
        });
    }
}
