<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreateAdBanner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_banner', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('site_id')->comment('站台ID');
            $table->string('lang', 5)->comment('語系');
            $table->string('name', 100)->comment('名稱');
            $table->string('image', 50)->comment('廣告圖路徑');
            $table->enum('status', [0, 1])->default(0)->comment('狀態');
            $table->tinyInteger('bysort')->comment('排序');
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
        Schema::dropIfExists('ad_banner');
    }
}
