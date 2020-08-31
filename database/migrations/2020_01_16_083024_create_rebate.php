<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRebate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rebate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->enum('preset', [0, 1])->default(0)->comment('是否為預設返水');
            $table->enum('type', ['valid_bet', 'won', 'loss'])->comment('返水類型，valid_bet=有效投注，won=贏退點，loss=輸退點');
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
        Schema::dropIfExists('rebate');
    }
}
