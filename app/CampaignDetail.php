<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignDetail extends Model
{
    //
    protected $fillable = ['campaign_id', 'asset_id', 'qty', 'created_at', 'updated_at'];

    public function campaignRecords()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function assetRecords()
    {
        return $this->belongsTo(Asset::class);
    }
}
