<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentGameConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_game_config', function (Blueprint $table) {
            $table->comment = '代理遊戲子項開關';
            $table->increments('id');
            $table->integer('agent_id', false, true);
            $table->integer('game_id', false, true);
            $table->enum('status', [0, 1])->default(0)->comment('0=停用，1=啟用');
            $table->timestamps();

            $table->unique(['agent_id', 'game_id'], 'unique');
            // $table->foreign('agent_id')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_game_config');
    }
}
