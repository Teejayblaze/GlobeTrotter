<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetType extends Model
{
    //

    public function asset()
    {
        return $this->hasMany(Asset::class);
    }
}
