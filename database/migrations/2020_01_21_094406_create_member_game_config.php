<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberGameConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_game_config', function (Blueprint $table) {
            $table->comment = '會員遊戲設定';
            $table->increments('id');
            $table->integer('member_id', false, true);
            $table->integer('game_id', false, true);
            $table->enum('status', [0, 1])->default(0);
            $table->timestamps();

            $table->unique(['member_id', 'game_id'], 'unique');
            // $table->foreign('member_id')->references('id')->on('member');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_game_config');
    }
}
