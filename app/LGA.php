<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LGA extends Model
{
    //
    protected $table = 'l_g_as';
    protected $fillable = ['state_id', 'lga_name'];
}
