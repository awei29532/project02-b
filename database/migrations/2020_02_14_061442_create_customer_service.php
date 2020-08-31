<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_service', function (Blueprint $table) {
            $table->comment = '客服帳號';
            $table->increments('id');
            $table->integer('user_id', false, true);
            $table->enum('level', [1, 2])->comment('1=客服主管，2=客服人員');
            $table->string('line_id', 20)->nullable();
            $table->text('line_qrcode')->nullable();
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
        Schema::dropIfExists('customer_service');
    }
}
