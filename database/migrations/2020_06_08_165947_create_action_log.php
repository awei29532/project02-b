<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreateActionLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_log', function (Blueprint $table) {
            $table->comment = '操作紀錄';
            $table->bigIncrements('id');
            $table->tinyInteger('action_type')->comment('操作者類型');
            $table->integer('action_id', false, true)->comment('操作者ID');
            $table->integer('action_level')->comment('操作者層級');
            $table->string('action_account', 20)->comment('操作者帳號');
            $table->string('action_name', 20)->comment('操作者名稱');
            $table->string('type', 50)->comment('類別');
            $table->text('content')->comment('執行內容');
            $table->string('ip', 20);
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
        Schema::dropIfExists('action_log');
    }
}
