<?php

use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends BaseSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->taskStart("Demo Seeder");

        Model::unguard();

        $this->call(RoleSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(AgentSeeder::class);
        $this->call(MemberSeeder::class);
        $this->call(AnnouncementSeeder::class);
        $this->call(CallbackSeeder::class);
        $this->call(ComplaintLetterSeeder::class);
        $this->call(GameCompanySeeder::class);
        $this->call(GameSeeder::class);
        $this->call(LevelSeeder::class);
        $this->call(MarqueeSeeder::class);
        $this->call(MessageSeeder::class);
        $this->call(RebateSeeder::class);
        $this->call(SiteConfigSeeder::class);
        $this->call(SmsSeeder::class);
        $this->call(PromotionSeeder::class);

        Model::reguard();

        $this->taskEnd("總執行時間: ");
    }
}
