<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberGameCompanyConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_game_company_config', function (Blueprint $table) {
            $table->comment = '會員遊戲商設定';
            $table->increments('id');
            $table->integer('member_id', false, true);
            $table->integer('company_id', false, true);
            $table->enum('status', [0, 1])->default(0);
            $table->timestamps();

            $table->unique(['member_id', 'company_id'], 'unique');
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
        Schema::dropIfExists('member_game_company_config');
    }
}
