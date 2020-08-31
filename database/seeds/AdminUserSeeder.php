<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->taskStart("AdminUserSeeder");

        $user = new User();
        $user->identity = 1;
        $user->username = 'admin';
        $user->password = Hash::make('123456');
        $user->nickname = 'god';
        $user->status = '1';
        $user->saveOrFail();

        $user->assignRole('admin');

        // 任務結束時間
        $this->taskEnd();
    }
}
