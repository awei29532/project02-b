<?php

use App\Models\Level;
use Carbon\Carbon;

class LevelSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->taskStart("LevelSeeder");

        $time = Carbon::now();
        Level::insert(
            [
                [
                    'name' => 'gold',
                    'created_at' => $time,
                    'updated_at' => $time,
                ],
                [
                    'name' => 'silver',
                    'created_at' => $time,
                    'updated_at' => $time,
                ],
                [
                    'name' => 'copper',
                    'created_at' => $time,
                    'updated_at' => $time,
                ],
            ]
        );

        // 任務結束時間
        $this->taskEnd();
    }
}
