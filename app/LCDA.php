<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LCDA extends Model
{
    //
    protected $table = 'l_c_d_as';


    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
