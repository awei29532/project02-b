<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreatePromotionContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_content', function (Blueprint $table) {
            $table->comment = '活動推廣內容(多語系內容)';
            $table->string('promotion_id', 50);
            $table->string('lang', 10)->comment('語系');
            $table->text('image')->comment('圖片路徑');
            $table->text('content')->comment('活動文字內容');
            $table->timestamps();

            $table->primary(['promotion_id', 'lang']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion_content');
    }
}
