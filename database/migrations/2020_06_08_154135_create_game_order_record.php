<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreateGameOrderRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_order_record', function (Blueprint $table) {
            $table->comment = '遊戲下注紀錄';
            $table->bigIncrements('id');
            $table->integer('site_id', false, true)->comment('站台ID');
            $table->string('order_no', 20)->comment('注單流水號');
            $table->integer('member_id', false, true);
            $table->integer('agent_id', false, true);
            $table->integer('company_id', false, true);
            $table->integer('game_id', false, true)->comment('遊戲ID');
            $table->string('game_type', 20)->comment('遊戲類型');
            $table->string('game_round', 20)->comment('遊戲局號');
            $table->string('game_play', 20)->comment('遊戲玩法');
            $table->string('game_currency', 20)->comment('貨幣類型');
            $table->string('game_table', 20)->comment('桌號');
            $table->double('before_amount', 15, 2)->comment('下注前額度');
            $table->double('amount', 15, 2)->comment('投注金額');
            $table->double('valid_amount', 15, 2)->comment('有效投注金額');
            $table->double('win', 15, 2)->comment('輸贏金額');
            $table->string('game_result', 500)->comment('結果');
            $table->dateTime('order_at')->comment('下注時間');
            $table->dateTime('draw_at')->comment('開獎時間');
            $table->string('ip', 20)->comment('下注IP');
            $table->string('device', 20)->comment('下注設備');
            $table->enum('is_valid', [0, 1])->comment('是否有效注單');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_order_record');
    }
}
