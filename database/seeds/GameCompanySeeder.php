<?php

use App\Models\Game;
use App\Models\GameCompany;
use App\Models\GameCompanyCommission;
use App\Models\GameCompanyMaintenance;

class GameCompanySeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->taskStart("GameCompanySeeder");
        
        GameCompany::create([
            'key' => 'yabo',
            'name' => '亞博',
            'status' => '1',
        ]);
        GameCompany::create([
            'key' => 'sa',
            'name' => 'SA',
            'status' => '1',
        ]);
        GameCompany::create([
            'key' => 'wm',
            'name' => 'WM',
            'status' => '1',
        ]);
        GameCompany::create([
            'key' => 'super',
            'name' => 'SUPER',
            'status' => '1',
        ]);
        GameCompany::create([
            'key' => 'dg',
            'name' => 'DG',
            'status' => '1',
        ]);
        GameCompany::create([
            'key' => 'allbet',
            'name' => '歐博',
            'status' => '1',
        ]);
        GameCompany::create([
            'key' => 'og',
            'name' => 'OG',
            'status' => '1',
        ]);
        GameCompany::create([
            'key' => 'afb88',
            'name' => 'AFB88',
            'status' => '1',
        ]);
        GameCompany::create([
            'key' => 'apl',
            'name' => 'APL',
            'status' => '1',
        ]);
        GameCompany::create([
            'key' => 'tw',
            'name' => 'TW',
            'status' => '1',
        ]);
        GameCompany::create([
            'key' => 'gd',
            'name' => '好路',
            'status' => '1',
        ]);
        GameCompany::create([
            'key' => 'ka',
            'name' => 'KA',
            'status' => '1',
        ]);
        GameCompany::create([
            'key' => 'rtg',
            'name' => 'RTG',
            'status' => '1',
        ]);
        GameCompany::create([
            'key' => 'gt',
            'name' => 'GT',
            'status' => '1',
        ]);

        // 任務結束時間
        $this->taskEnd();
    }
}
