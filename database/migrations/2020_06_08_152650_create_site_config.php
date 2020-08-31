<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreateSiteConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_config', function (Blueprint $table) {
            $table->comment = '站台參數';
            $table->integer('site_id', false, true)->comment('站台ID');
            $table->string('name', 30)->comment('名稱');
            $table->text('value')->comment('參數');
            $table->string('info')->comment('說明');
            $table->timestamps();

            $table->primary(['site_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_config');
    }
}
