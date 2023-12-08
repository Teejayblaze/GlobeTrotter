<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminGroup extends Model
{
    //

    public function role()
    {
        return $this->hasMany(AdminRole::class, 'admin_user_group_id');
    }
}
