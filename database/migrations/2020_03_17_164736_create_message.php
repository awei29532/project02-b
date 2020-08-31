<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreateMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message', function (Blueprint $table) {
            $table->comment = '個人訊息';
            $table->bigIncrements('id');
            $table->tinyInteger('site_id')->comment('站台ID');
            $table->text('member_ids')->comment('傳送對象');
            $table->text('title');
            $table->text('content');
            $table->enum('status', [0, 1])->default(0)->comment('0=未傳送，1=已傳送');
            $table->dateTime('send_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message');
    }
}
