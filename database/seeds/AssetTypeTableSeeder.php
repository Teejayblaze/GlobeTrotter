<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AssetTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $assetTypes = [
            ['id' => 1, 'type' => 'LAMP POST', 'board_type' => 1, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'type' => 'MURALS', 'board_type' => 1, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'type' => 'ROOF TOPS', 'board_type' => 1, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'type' => 'UNIPOLE', 'board_type' => 1, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'type' => 'GANTRY', 'board_type' => 1, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 6, 'type' => '48 SHEET', 'board_type' => 1, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 7, 'type' => '96 SHEET', 'board_type' => 1, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 8, 'type' => 'EYE CATCHER', 'board_type' => 1, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 9, 'type' => 'BUS SHELTER', 'board_type' => 1, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 10, 'type' => 'WALL DRAPE', 'board_type' => 1, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 11, 'type' => 'FRONT LIT', 'board_type' => 1, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 12, 'type' => 'BACK LIT', 'board_type' => 1, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 13, 'type' => 'ICONIC', 'board_type' => 1, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 14, 'type' => 'MUPPIES', 'board_type' => 1, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            

            ['id' => 15, 'type' => 'TRI-VISION / ULTRA-WEAVES', 'board_type' => 2, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 16, 'type' => 'SCROLLERS', 'board_type' => 2, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 17, 'type' => 'BLIMPS', 'board_type' => 2, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 18, 'type' => 'CINEMAS', 'board_type' => 2, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            

            ['id' => 19, 'type' => 'CINEMAS', 'board_type' => 3, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 20, 'type' => 'MOBILE LEDs', 'board_type' => 3, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 21, 'type' => 'LAMP POST', 'board_type' => 3, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 22, 'type' => 'MURALS', 'board_type' => 3, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 23, 'type' => 'ROOF TOPS', 'board_type' => 3, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 24, 'type' => 'UNIPOLE', 'board_type' => 3, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 25, 'type' => 'GANTRY', 'board_type' => 3, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 26, 'type' => '48 SHEET', 'board_type' => 3, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 27, 'type' => '96 SHEET', 'board_type' => 3, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 28, 'type' => 'EYE CATCHER', 'board_type' => 3, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 29, 'type' => 'BUS SHELTER', 'board_type' => 3, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 30, 'type' => 'WALL DRAPE', 'board_type' => 3, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 31, 'type' => 'FRONT LIT', 'board_type' => 3, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 32, 'type' => 'BACK LIT', 'board_type' => 3, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 33, 'type' => 'ICONIC', 'board_type' => 3, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],


            ['id' => 34, 'type' => 'TRICYCLE', 'board_type' => 4, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 35, 'type' => 'TAXIS', 'board_type' => 4, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 36, 'type' => 'TRUCKS', 'board_type' => 4, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 37, 'type' => 'ADVERTISING BIKES', 'board_type' => 4, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 38, 'type' => 'BUSES', 'board_type' => 4, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 39, 'type' => 'TRAIN', 'board_type' => 4, 'enabled' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        foreach($assetTypes as $assetType) {
            \App\AssetType::create($assetType);
        }
    }
}
