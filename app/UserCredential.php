<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserCredential extends Authenticatable
{
    //
    use Notifiable;

    protected $table = 'user_credentials';


    public function user_type()
    {
        return $this->belongsTo(\App\UserType::class);
    }

}
