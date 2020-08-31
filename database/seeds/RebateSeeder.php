<?php

use App\Models\Rebate;
use Illuminate\Database\Seeder;

class RebateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rebate::create([
            'name' => 'preset_rebate',
            'preset' => '1',
            'type' => 'valid_bet',
        ]);
    }
}
