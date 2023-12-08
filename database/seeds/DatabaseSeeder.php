<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserTypeTableSeeder::class,
            AssetTypeTableSeeder::class,
            KeywordTableSeeder::class,
            AdminGroupsTableSeeder::class,
            AdminRolesTableSeeder::class,
        ]);
    }
}
