<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreateMarquee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marquee', function (Blueprint $table) {
            $table->comment = '跑馬燈';
            $table->bigIncrements('id');
            $table->tinyInteger('site_id')->comment('站台ID');
            $table->string('lang')->comment('語系');
            $table->text('content')->nullable()->comment('內容');
            $table->text('url')->nullable()->comment('連結');
            $table->enum('status', [0, 1])->default(1)->comment('0=停用，1=啟用');
            $table->tinyInteger('bysort')->default(1)->comment('排序');
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
        Schema::dropIfExists('marquee');
    }
}
