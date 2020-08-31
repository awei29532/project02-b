<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreateComplaintLetter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_letter', function (Blueprint $table) {
            $table->comment = '投訴信件';
            $table->increments('id');
            $table->tinyInteger('site_id')->comment('站台ID');
            $table->integer('member_id', false, true);
            $table->string('title', 20);
            $table->text('content');
            $table->enum('status', [0, 1])->default(0)->comment('狀態，0=未讀，1=已讀');
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
        Schema::dropIfExists('complaint_letter');
    }
}
