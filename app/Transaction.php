<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    public function asset_booking()
    {
        return $this->belongsTo(AssetBooking::class);
    }
}
