<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreateMemberDeposit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_deposit', function (Blueprint $table) {
            $table->comment = '會員存款單紀錄';
            $table->bigIncrements('sn')->comment('uuid');
            $table->integer('member_id', false, true);
            $table->integer('bank_card_id', false, true)->comment('公司銀行卡編號');
            $table->string('order_no', 20)->comment('訂單號');
            $table->string('remark', 10)->nullable();
            $table->tinyInteger('type')->default(0)->comment('公司入款類型');
            $table->tinyInteger('cash_id')->default(0)->comment('金流代號');
            $table->double('amount', 15, 2);
            $table->string('username', 20)->comment('存款人姓名');
            $table->tinyInteger('deposit_type')->default(0)->comment('存款方式');
            $table->string('member_bank', 20)->comment('會員使用銀行');
            $table->enum('status', [0, 1])->default(0)->comment('處理狀態');
            $table->dateTime('deposit_at')->nullable()->comment('存入時間');
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
        Schema::dropIfExists('member_deposit');
    }
}
