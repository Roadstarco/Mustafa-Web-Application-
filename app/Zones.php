<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zones extends Model
{
    /** yes
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'complete_address', 'city', 'state', 'country', 'currency', 'status',
        'zone_area',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
