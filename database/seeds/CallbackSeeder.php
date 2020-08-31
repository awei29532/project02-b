<?php

use App\Models\Callback;

class CallbackSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->taskStart("CallbackSeeder");

        factory(Callback::class, 10)->create();

        // 任務結束時間
        $this->taskEnd();
    }
}
