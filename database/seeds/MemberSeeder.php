<?php

use App\Models\Member;
use App\Models\MemberWallet;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->taskStart("MemberSeeder");

        $member = new Member();
        $member->site_id = 1;
        $member->agent_id = 1;
        $member->username = 'member01';
        $member->password = Hash::make('123456');
        $member->level_id = rand(1, 3);
        $member->rebate_id = 1;
        $member->register_ip = '127.0.0.1';
        $member->generatorInvitationCode();
        $member->bind_code = 'A12345';
        $member->saveOrFail();

        $wallet = new MemberWallet();
        $wallet->member_id = $member->id;
        $wallet->saveOrFail();

        // 任務結束時間
        $this->taskEnd();
    }
}
