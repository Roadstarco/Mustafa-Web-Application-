<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model {


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trips';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id',
        'provider_id',
        'user_id',
        'tripfrom',
        'tripto',
        'arrival_date',
        'recurrence',
        'item',
        'item_size',
        'item_type',
        'other_information',
        'service_type',
        'vessel_id',
        'vessel_imo',
        'vessel_name',
        'source_port_id',
        'destination_port_id',
        'flight_no',
        'departure_time',
        'airport',
        'tripfrom_lat',
        'tripfrom_lng',
        'tripto_lat',
        'tripto_lng',
        'trip_amount',
        'trip_status',
        'created_by',
        'receiver_name',
        'receiver_phone',
    ];


    /**
     * The user who created the request.
     */
    public function user() {
        return $this->belongsTo('App\User');
    }
    
    public function payment() {
        return $this->hasOne('App\TripPayments');
    }
    
     public function tripRequest() {
        return $this->hasOne('App\TripRequests');
    }

     public function provider()
    {
        return $this->belongsTo('App\Provider');
    }

    public function sea_trip_estimated_arrival() {
        return $this->hasOne('App\SeaTripEstimatedArrival');
    }

    public function air_trip_flight_info() {
        return $this->hasOne('App\AirTripFlightInfo');
    }

}
