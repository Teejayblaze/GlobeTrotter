<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetImage extends Model
{
    //

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
