<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGame extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id', false, true);
            $table->string('type', 10)->comment('遊戲類型');
            $table->string('game_code', 20);
            $table->enum('status', [0, 1])->default(0)->comment('0=停用，1=啟用');
            $table->timestamps();

            // $table->foreign('company_id')->references('id')->on('game_company');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game');
    }
}
