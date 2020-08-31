<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreateGameCompanyCommission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_company_commission', function (Blueprint $table) {
            $table->comment = '遊戲商佔成';
            $table->unsignedInteger('company_id')->primary()->comment('遊戲商ID');
            $table->decimal('ratio_win', 8, 5)->default(0)->comment('輸贏獲利');
            $table->decimal('ratio_lose', 8, 5)->default(0)->comment('輸贏虧損');
            $table->enum('status', [0, 1])->default(0)->comment('0=不顯示，1=顯示');
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
        Schema::dropIfExists('game_company_commission');
    }
}
