<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLevel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('level', function (Blueprint $table) {
            $table->comment = '會員等級';
            $table->increments('id');
            $table->string('name', 50)->comment('等級名稱');
            $table->string('remark', 100)->nullable();
            $table->timestamps();

            $table->unique('name', 'name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('level');
    }
}
