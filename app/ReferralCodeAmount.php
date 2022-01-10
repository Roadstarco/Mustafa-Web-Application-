<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferralCodeAmount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'referral_code_amount_to_user', 'referral_code_amount_by_user'
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
