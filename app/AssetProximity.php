<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetProximity extends Model
{
    //
    protected $fillable = ['proximity_type','proximity_name','asset_id','created_at','updated_at'];
}
