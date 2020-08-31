<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreateSms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms', function (Blueprint $table) {
            $table->comment = '簡訊';
            $table->bigIncrements('id');
            $table->tinyInteger('site_id')->comment('站台ID');
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
        Schema::dropIfExists('sms');
    }
}
