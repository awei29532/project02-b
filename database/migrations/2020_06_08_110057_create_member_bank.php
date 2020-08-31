<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreateMemberBank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_bank', function (Blueprint $table) {
            $table->comment = '會員銀行卡';
            $table->bigIncrements('id');
            $table->string('sn', 20)->comment('uuid');
            $table->integer('member_id', false, true);
            $table->integer('type', false, true)->default(1)->comment('銀行卡類型，1:銀行卡，2=USDT');
            $table->string('bank_name', 10)->comment('銀行代碼');
            $table->string('branch_name', 30)->comment('分行名稱');
            $table->string('account', 30)->comment('帳號');
            $table->string('name', 30)->comment('戶名');
            $table->timestamps();

            $table->unique('sn', 'uuid');
            $table->unique('account', 'account');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_bank');
    }
}
