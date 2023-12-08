<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    //

    public function assetTypeRecord()
    {
        return $this->belongsTo(AssetType::class, 'asset_type');
    }

    public function assetImagesRecord()
    {
        return $this->hasMany(AssetImage::class, 'asset_id');
    }

    public function assetKeywordRecord()
    {
        return $this->belongsTo(Keywords::class, 'asset_search_keyword');
    }

    public function assetBookingsRecords()
    {
        return $this->hasMany(AssetBooking::class, 'asset_id');
    }

    public function assetStateRecords()
    {
        return $this->belongsTo(State::class, 'location_state');
    }

    public function assetLGARecords()
    {
        return $this->belongsTo(LGA::class, 'location_lga');
    }

    public function assetLCDARecords()
    {
        return $this->belongsTo(LCDA::class, 'location_lcda');
    }
    
    public function assetOwner()
    {
        return $this->belongsTo(Operator::class, 'uploaded_by');
    }

    public function assetProximityRecords()
    {
        return $this->hasMany(AssetProximity::class, 'asset_id');
    }

    public function assetPromoRecords()
    {
        return $this->hasMany(AssetPromo::class, 'asset_id');
    }

    public function campaignRecords()
    {
        return $this->hasMany(CampaignDetail::class, 'asset_id');
    }
}
