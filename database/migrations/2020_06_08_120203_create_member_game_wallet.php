<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreateMemberGameWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_game_wallet', function (Blueprint $table) {
            $table->comment = '會員遊戲額度';
            $table->integer('member_id', false, true);
            $table->integer('company_id', false, true);
            $table->double('amount', 15, 2)->default(0);
            $table->timestamps();

            $table->primary(['member_id', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_game_wallet');
    }
}
