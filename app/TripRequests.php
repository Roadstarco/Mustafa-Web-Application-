<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TripRequests extends Model

{
        /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trip_requests';

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
        'user_id',
        'provider_id',
        'trip_id',
        'send_from',
        'send_to',
        'item',
        'item_type',
        'item_size',
        'picture1',
        'picture2',
        'picture3',
        'description',
        'amount',
        'traveller_response',
        'service_type',
        'created_by',
        'is_counter'
       
    ];
}