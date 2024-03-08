<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    //

    public function campaignDetails()
    {
        return $this->hasMany(CampaignDetail::class, 'campaign_id');
    }

    public function transaction() 
    {
        return $this->hasMany(Transaction::class, 'campaign_id');
    }
}
