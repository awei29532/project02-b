<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLoginLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_login_log', function (Blueprint $table) {
            $table->comment = '控端登入紀錄';
            $table->increments('id');
            $table->integer('user_id', false, true);
            $table->string('device', 10)->nullable();
            $table->string('browser', 10)->nullable();
            $table->string('ip', 50);
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
        Schema::dropIfExists('user_login_log');
    }
}
