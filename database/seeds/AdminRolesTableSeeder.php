<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AdminRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRoles = [
            ['id' => 1, 'admin_user_group_id' => 1, 'component' => 'dashboard', 'can_view' => 1, 'can_update' => 1, 'can_create' => 1, 'can_delete' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'admin_user_group_id' => 2, 'component' => 'dashboard', 'can_view' => 1, 'can_update' => 1, 'can_create' => 1, 'can_delete' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'admin_user_group_id' => 3, 'component' => 'dashboard', 'can_view' => 1, 'can_update' => 1, 'can_create' => 1, 'can_delete' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'admin_user_group_id' => 4, 'component' => 'dashboard', 'can_view' => 1, 'can_update' => 1, 'can_create' => 1, 'can_delete' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'admin_user_group_id' => 5, 'component' => 'dashboard', 'can_view' => 1, 'can_update' => 1, 'can_create' => 1, 'can_delete' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        foreach($adminRoles as $adminRole) {
            \App\AdminRole::create($adminRole);
        }
    }
}
