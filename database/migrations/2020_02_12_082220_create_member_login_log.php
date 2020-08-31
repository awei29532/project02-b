<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberLoginLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_login_log', function (Blueprint $table) {
            $table->comment = '會員登入記錄';
            $table->increments('id');
            $table->integer('member_id', false, true);
            $table->string('device', 50)->nullable();
            $table->string('browser', 50)->nullable();
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
        Schema::dropIfExists('member_login_log');
    }
}
