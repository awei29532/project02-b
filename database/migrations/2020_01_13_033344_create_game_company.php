<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_company', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->string('key', 10);
            $table->string('name', 50);
            $table->enum('status', [0, 1])->default(0)->comment('0=停用，1=啟用');
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
        Schema::dropIfExists('game_company');
    }
}
