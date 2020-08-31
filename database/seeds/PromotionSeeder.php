<?php

use App\Models\Promotion;
use App\Models\PromotionContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $promotion = new Promotion();
        $promotion->id = DB::raw('UUID()');
        $promotion->site_id = 1;
        $promotion->name = '註冊優惠';
        $promotion->add_amount = 10;
        $promotion->bet_multiple = 10;
        $promotion->bet_required_amount = 10;
        $promotion->bet_valid_day = 10;
        $promotion->status = '1';
        $promotion->image = '123';
        $promotion->content = '123';
        $promotion->start_at = date('Y-m-d H:i:s');
        $promotion->end_at = date('Y-m-d H:i:s');
        $promotion->saveOrFail();

        $latest = Promotion::first();

        $promotion_content = new PromotionContent();
        $promotion_content->promotion_id = $latest->id;
        $promotion_content->lang = 'tw-zh';
        $promotion_content->image = '123';
        $promotion_content->content = '123';
        $promotion_content->saveOrFail();
    }
}
