<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    //

    public function corporate()
    {
        return $this->belongsTo(\App\Corporate::class, 'corp_id');
    }
}
