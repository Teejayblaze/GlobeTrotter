<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetBooking extends Model
{
    //
    protected $fillable = ['locked'];

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'asset_booking_id');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function assetGracePeriod()
    {
        return $this->hasMany(assetGracePeriod::class, 'asset_booking_id');
    }
}
