<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreateMemberTransactionRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_transaction_record', function (Blueprint $table) {
            $table->comment = '會員交易紀錄';
            $table->bigIncrements('id');
            $table->tinyInteger('tran_type', false, true)->comment('交易類型');
            $table->string('tran_no', 100)->comment('交易序號');
            $table->integer('from_mid', false, true)->comment('轉出會員');
            $table->string('from_account', 20)->comment('轉出帳戶');
            $table->double('from_balance', 15, 2)->comment('轉出原始餘額');
            $table->integer('to_mid', false, true)->comment('轉入會員');
            $table->string('to_account', 20)->comment('轉入帳戶');
            $table->double('to_balance', 15, 2)->comment('傳入帳戶餘額');
            $table->double('amount', 15, 2);
            $table->tinyInteger('operator_type')->comment('操作者類型');
            $table->integer('operator', false, true)->comment('操作者ID');
            $table->text('remark')->nullable();
            $table->timestamps();

            $table->unique('tran_no', 'tran_no');
            $table->index('tran_type');
            $table->index('from_mid');
            $table->index('to_mid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_transaction_record');
    }
}
