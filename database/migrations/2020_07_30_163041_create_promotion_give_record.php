<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreatePromotionGiveRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_give_record', function (Blueprint $table) {
            $table->comment = '活動放發記錄';
            $table->string('promotion_id', 50);
            $table->tinyInteger('site_id')->comment('站台ID');
            $table->integer('member_id')->comment('會員ID');
            $table->double('amount')->comment('派發點數');
            $table->integer('give_user_id')->comment('發放人員');
            $table->timestamp('give_at')->comment('發放時間');

            $table->primary(['promotion_id', 'site_id', 'member_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion_give_record');
    }
}
