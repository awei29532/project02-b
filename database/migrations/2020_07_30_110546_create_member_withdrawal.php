<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreateMemberWithdrawal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_withdrawal', function (Blueprint $table) {
            $table->comment = '會員出款單紀錄';
            $table->string('sn')->comment('uuid')->primary();
            $table->tinyInteger('site_id', false, true)->comment('站台ID');
            $table->integer('member_id', false, true)->comment('會員ID');
            $table->double('amount', 15, 2)->comment('出款金額');
            $table->double('fee', 15, 2)->comment('手續費');
            $table->string('remark', 500)->comment('備註');
            $table->enum('review', [0, 1])->comment('0=待審核，1=審核中(lock)');
            $table->enum('status', [0, 1, 2, 3])->comment('0=未處理，1=未通過，2=通過，3=已發放');
            $table->dateTime('withdrawal_at')->comment('出款時間');
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
        Schema::dropIfExists('member_withdrawal');
    }
}
