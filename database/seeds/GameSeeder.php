<?php

use App\Models\Game;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # yabo
        Game::create([
            'company_id' => 1,
            'game_code' => '亞博視訊',
            'type' => 'yabo',
            'status' => '1',
        ]);
        Game::create([
            'company_id' => 1,
            'game_code' => '亞博彩票',
            'type' => 'yabo',
            'status' => '1',
        ]);
        Game::create([
            'company_id' => 1,
            'game_code' => '亞博棋牌',
            'type' => 'yabo',
            'status' => '1',
        ]);
        Game::create([
            'company_id' => 1,
            'game_code' => '亞博視訊',
            'type' => 'yabo',
            'status' => '1',
        ]);

        # live
        Game::create([
            'company_id' => 5,
            'game_code' => 'DG-live',
            'type' => 'live',
            'status' => '1',
        ]);
        Game::create([
            'company_id' => 3,
            'game_code' => 'WM-live',
            'type' => 'live',
            'status' => '1',
        ]);
        Game::create([
            'company_id' => 2,
            'game_code' => 'SA-live',
            'type' => 'live',
            'status' => '1',
        ]);
        Game::create([
            'company_id' => 6,
            'game_code' => 'allbet-live',
            'type' => 'live',
            'status' => '1',
        ]);
        Game::create([
            'company_id' => 7,
            'game_code' => 'OG-live',
            'type' => 'live',
            'status' => '1',
        ]);

        # sport
        Game::create([
            'company_id' => 4,
            'game_code' => 'super-sport',
            'type' => 'sport',
            'status' => '1',
        ]);
        Game::create([
            'company_id' => 8,
            'game_code' => 'afb88-sport',
            'type' => 'sport',
            'status' => '1',
        ]);

        # lottery
        Game::create([
            'company_id' => 9,
            'game_code' => 'apl-lottery',
            'type' => 'lottery',
            'status' => '1',
        ]);
        Game::create([
            'company_id' => 4,
            'game_code' => 'super-lottery',
            'type' => 'lottery',
            'status' => '1',
        ]);
        Game::create([
            'company_id' => 10,
            'game_code' => 'tw-lottery',
            'type' => 'lottery',
            'status' => '1',
        ]);
        Game::create([
            'company_id' => 14,
            'game_code' => 'gt-lottery',
            'type' => 'lottery',
            'status' => '1',
        ]);

        # chess
        Game::create([
            'company_id' => 11,
            'game_code' => 'gd-chess',
            'type' => 'chess',
            'status' => '1',
        ]);
    }
}
