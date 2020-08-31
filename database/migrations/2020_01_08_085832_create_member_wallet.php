<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_wallet', function (Blueprint $table) {
            $table->integer('member_id', false, true)->primary();
            $table->double('amount', 15, 2)->default(0);
            $table->double('freeze_amount', 15, 2)->default(0)->comment('凍結金額');
            $table->timestamps();

            // $table->foreign('member_id')->references('id')->on('member');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_wallet');
    }
}
