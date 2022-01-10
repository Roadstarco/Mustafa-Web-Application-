<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TripPayments extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trip_payments';

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
        'trip_id',
        'bid_id',
        'provider_id',
        'fixed',
        'commision',
        'provider_commission',
        'tax',
        'total',
        'payment_mode',
        'payment_id',
        'card_id'
    ];

    public function trip() {
        return $this->belongsTo('App\Trip');
    }

}
