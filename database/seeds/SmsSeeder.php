<?php

use App\Models\Sms;
use Illuminate\Database\Seeder;

class SmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Sms::class, 10)->create();
    }
}
