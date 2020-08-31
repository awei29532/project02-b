<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_wallet', function (Blueprint $table) {
            $table->integer('agent_id', false, true)->primary();
            $table->double('amount', 15, 2)->default(0);
            $table->timestamps();

            // $table->foreign('agent_id')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_wallet');
    }
}
