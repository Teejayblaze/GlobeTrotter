<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userTypes = [
            ['id' => 1,  'name' => 'CORPORATE', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'name' => 'INDIVIDUAL', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        foreach ( $userTypes as $userType ) {
            \App\UserType::create($userType);
        }
    }
}
