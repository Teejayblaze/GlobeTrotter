<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetGracePeriod extends Model
{
    //
    protected $fillable = [
        'asset_booking_id', 'booked_id', 'percentage', 'grace_period_started', 'grace_period_ends', 'completed', 'created_at', 'updated_at'
    ];
}
