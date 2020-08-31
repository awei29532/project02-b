<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion', function (Blueprint $table) {
            $table->comment = '活動推廣設定';
            $table->string('id', 50)->comment('uuid');
            $table->tinyInteger('site_id')->comment('站台ID');
            $table->string('name', 50)->comment('名稱');
            $table->double('add_amount', false, true)->comment('派發點數');
            $table->integer('bet_multiple', false, true)->comment('流水倍數');
            $table->double('bet_required_amount', false, true)->comment('流水要求');
            $table->tinyInteger('bet_valid_day')->comment('有效天數');
            $table->enum('status', [0, 1])->default(0)->comment('啟停用');
            $table->string('image', 50)->comment('圖片路徑');
            $table->text('content')->comment('活動文字內容');
            $table->dateTime('start_at')->comment('開始時間');
            $table->dateTime('end_at')->comment('結束時間');
            $table->timestamps();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion');
    }
}
