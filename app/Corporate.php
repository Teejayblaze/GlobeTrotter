<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Corporate extends Model
{
    //

    public function individuals()
    {
        return $this->hasMany(\App\Individual::class);
    }
}
