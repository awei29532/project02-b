<?php

use Spatie\Permission\Models\Role;

class RoleSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->taskStart("RoleSeeder");

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'admin_sub']);
        Role::create(['name' => 'owner']);
        Role::create(['name' => 'owner_sub']);
        Role::create(['name' => 'agent']);
        Role::create(['name' => 'agent_sub']);
        Role::create(['name' => 'customer-service-supervisor']);
        Role::create(['name' => 'customer-service']);

        // 任務結束時間
        $this->taskEnd();
    }
}
