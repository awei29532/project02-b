<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRebateConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rebate_config', function (Blueprint $table) {
            $table->comment = '返水設置';
            $table->integer('rebate_id', false, true);
            $table->integer('company_id', false, true);
            $table->double('amount')->comment('金額');
            $table->float('percentage')->comment('返水比例');
            $table->timestamps();

            // $table->foreign('rebate_id')->references('id')->on('rebate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rebate_config');
    }
}
