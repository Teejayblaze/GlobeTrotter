<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class KeywordTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $keywords = [
            ['id' => 1, 'keyword' => 'CHURCH', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'keyword' => 'MOSQUE', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'keyword' => 'RECREATION CENTER', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'keyword' => 'FILLING STATION', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'keyword' => 'CINEMA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 6, 'keyword' => 'EATERY', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 7, 'keyword' => 'BUS/STOP', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        foreach( $keywords as $keyword ) {
            \App\Keywords::create($keyword);
        }
    }
}
