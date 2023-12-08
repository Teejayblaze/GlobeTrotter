<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    //

    public function admingroup()
    {
        return $this->belongsTo(AdminGroup::class);
    }
}
