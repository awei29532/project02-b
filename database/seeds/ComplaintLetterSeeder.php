<?php

use App\Models\ComplaintLetter;
use Illuminate\Database\Seeder;

class ComplaintLetterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ComplaintLetter::class, 100)->create();
    }
}
