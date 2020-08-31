<?php

use App\Models\Agent;
use App\Models\AgentWallet;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # top company
        $user = new User();
        $user->identity = 3;
        $user->username = 'agent01';
        $user->password = Hash::make('123456');
        $user->nickname = 'TopCompany';
        $user->saveOrFail();

        $parent = new Agent();
        $parent->site_id = 1;
        $parent->user_id = $user->id;
        $parent->level = 1;
        $parent->invitation_code = 'A12345';
        $parent->saveAsRoot();

        $wallet = new AgentWallet();
        $wallet->agent_id = $parent->id;
        $wallet->saveOrFail();

        # company
        $user = new User();
        $user->identity = 3;
        $user->username = 'agent02';
        $user->password = Hash::make('123456');
        $user->nickname = 'Company';
        $user->saveOrFail();

        $agent = new Agent();
        $agent->site_id = 1;
        $agent->user_id = $user->id;
        $agent->level = 1;
        $agent->generatorInvitationCode($user->username);
        $agent->appendToNode($parent)->saveOrFail();

        $wallet = new AgentWallet();
        $wallet->agent_id = $agent->id;
        $wallet->saveOrFail();
    }
}
