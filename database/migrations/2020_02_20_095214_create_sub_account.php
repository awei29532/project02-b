<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreateSubAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_account', function (Blueprint $table) {
            $table->comment = '子帳號';
            $table->integer('user_id', false, true)->unique()->comment('對應之user_id');
            $table->integer('extend_id', false, true)->comment('對應之主帳號user_id');
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
        Schema::dropIfExists('sub_account');
    }
}
