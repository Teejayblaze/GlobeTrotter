<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AdminGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminGroups = [
            ['id' => 1, 'group_name' => 'SUPER', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'group_name' => 'TECHNICAL', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'group_name' => 'FINANCIAL', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'group_name' => 'COMPLIANCE', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'group_name' => 'COMPLAINT/CUSTOMER CARE', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        foreach($adminGroups as $adminGroup) {
            \App\AdminGroup::create($adminGroup);
        }
    }
}
