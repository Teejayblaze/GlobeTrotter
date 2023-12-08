<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminUserCredential extends model
{
    //
    protected $table = 'admin_user_credentials';

    public function admingroup()
    {
        return $this->belongsTo(AdminGroup::class, 'admin_group_id');
    }

    public function userdetails()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }
}
