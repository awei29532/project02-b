<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameMaintenance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_maintenance', function (Blueprint $table) {
            $table->integer('site_id', false, true);
            $table->integer('game_id', false, true);
            $table->integer('company_id', false, true);
            $table->string('game_type', 10);
            $table->enum('status', [0, 1])->default(0)->comment('0=停用，1=啟用');
            $table->enum('maintain_type', ['routine', 'temporary'])->nullable()->comment('routine=例行維護，temporary=臨時維護');
            $table->string('start_at', 20)->nullable()->comment('開始時間');
            $table->string('end_at', 20)->nullable()->comment('結束時間');
            $table->text('remark')->nullable()->comment('備註');
            $table->timestamps();

            $table->primary(['site_id', 'game_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_maintenance');
    }
}
