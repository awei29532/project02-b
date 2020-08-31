<?php

use App\Models\SiteConfig;
use Illuminate\Database\Seeder;

class SiteConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $siteConfig = new SiteConfig();

        $siteConfig->site_id = 1;
        $siteConfig->name = 'site_menu';
        $siteConfig->value = json_encode([
            [
                'key' => 'yabo',
                'name' => '亞博廳',
                'status' => 1,
                'companies' => [
                    [
                        'key' => 'yabo-live',
                        'name' => '亞博真人',
                        'status' => 1,
                    ],
                    [
                        'key' => 'yabo-lottery',
                        'name' => '亞博彩票',
                        'status' => 1,
                    ],
                    [
                        'key' => 'yabo-chess',
                        'name' => '亞博棋牌',
                        'status' => 1,
                    ]
                ],
            ],
            [
                'key' => 'live',
                'name' => '真人',
                'status' => 1,
                'companies' => [
                    [
                        'key' => 'dg-live',
                        'name' => 'DG真人',
                        'status' => 1,
                    ],
                    [
                        'key' => 'wm-live',
                        'name' => 'WM真人',
                        'status' => 1,
                    ],
                    [
                        'key' => 'sa-live',
                        'name' => 'SA真人',
                        'status' => 1,
                    ],
                    [
                        'key' => 'allbet-live',
                        'name' => '歐博真人',
                        'status' => 1,
                    ],
                    [
                        'key' => 'og-live',
                        'name' => 'OG真人',
                        'status' => 1,
                    ],
                ]
            ],
            [
                'key' => 'sport',
                'name' => '體育',
                'status' => 1,
                'companies' => [
                    [
                        'key' => 'super-sport',
                        'name' => 'SUPER體育',
                        'status' => 1,
                    ],
                    [
                        'key' => 'afb88-sport',
                        'name' => 'AFB88體育',
                        'status' => 1,
                    ],
                ]
            ],
            [
                'key' => 'lottery',
                'name' => '彩票',
                'status' => 1,
                'companies' => [
                    [
                        'key' => 'apl-lottery',
                        'name' => 'APL彩票',
                        'status' => 1,
                    ],
                    [
                        'key' => 'super-lottery',
                        'name' => 'SUPER六合彩',
                        'status' => 1,
                    ],
                    [
                        'key' => 'taiwan-bingo',
                        'name' => '台灣賓果',
                        'status' => 1,
                    ],
                ]
            ],
            [
                'key' => 'chess',
                'name' => '棋牌',
                'status' => 1,
                'companies' => [
                    [
                        'key' => 'gd-chess',
                        'name' => '好路棋牌',
                        'status' => 1,
                    ],
                ]
            ],
            [
                'key' => 'electron',
                'name' => '電子',
                'status' => 1,
                'companies' => [
                    [
                        'key' => 'ka-electron',
                        'name' => 'KA電子',
                        'status' => 1,
                    ],
                    [
                        'key' => 'apl-electron',
                        'name' => 'APL電子',
                        'status' => 1,
                    ],
                    [
                        'key' => 'sa-electron',
                        'name' => 'SA電子',
                        'status' => 1,
                    ],
                    [
                        'key' => 'rtg-electron',
                        'name' => 'RTG電子',
                        'status' => 1,
                    ],
                ]
            ],
        ]);
        $siteConfig->info = '站台遊戲選單';
        $siteConfig->saveOrFail();
    }
}
