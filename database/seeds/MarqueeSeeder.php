<?php

use App\Models\Marquee;
use Illuminate\Database\Seeder;

class MarqueeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Marquee::class, 5)->create();
    }
}
