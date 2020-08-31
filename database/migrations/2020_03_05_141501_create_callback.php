<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreateCallback extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('callback', function (Blueprint $table) {
            $table->comment = '回電服務';
            $table->increments('id');
            $table->tinyInteger('site_id')->comment('站台ID');
            $table->integer('member_id', false, true)->nullable();
            $table->string('phone', 50);
            $table->string('ip', 50);
            $table->enum('status', [0, 1, 2])->default(0)->comment('0=未處理，1=已處理，2=封鎖');
            $table->integer('cs_id', false, true)->nullable()->comment('承接客服ID');
            $table->dateTime('callback_at')->nullable()->comment('承接時間');
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('callback');
    }
}
