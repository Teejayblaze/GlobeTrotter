<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    //

    public function user_credential()
    {
        return $this->hasMany(\App\UserCredential::class, 'user_type_id');
    }
}
