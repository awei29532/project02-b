<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMember extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id', false, true);
            $table->integer('agent_id', false, true)->default(0)->comment('上層代理ID，0=直接註冊玩家');
            $table->string('username', 100);
            $table->string('password', 100);
            $table->string('name', 100)->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->enum('status', [0, 1])->default(1)->comment('0=凍結，1=啟用');
            $table->integer('level_id', false, true)->default(0)->comment('會員等級ID');
            $table->integer('rebate_id', false, true)->default(0)->comment('返水ID');
            $table->string('cell_phone', 50)->nullable();
            $table->string('id_number', 50)->nullable();
            $table->string('country', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('line', 50)->nullable();
            $table->string('remark', 100)->nullable();
            $table->string('register_ip', 100);
            $table->date('birthday')->nullable();
            $table->string('invitation_code', 6)->comment('推廣代碼');
            $table->string('bind_code', 6)->comment('綁定代碼');
            $table->softDeletes();
            $table->timestamps();

            $table->unique('username', 'username');
            $table->unique('cell_phone', 'cell_phone');
            $table->unique('id_number', 'id_number');
            $table->unique('email', 'email');
            $table->unique('line', 'line');
            $table->unique('invitation_code', 'invitation_code');
            $table->index('bind_code', 'bind_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member');
    }
}
