<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->comment = '後臺使用者';
            $table->increments('id');
            $table->enum('identity', [1, 2, 3])->comment('roles id，1=admin，2=customer service，3=agent');
            $table->string('username', 50);
            $table->string('password', 100);
            $table->string('nickname', 50)->nullable();
            $table->enum('status', [0, 1])->default(1)->comment('0=停用，1=啟用');
            $table->string('image', 100)->nullable()->comment('大頭貼');
            $table->timestamps();

            $table->unique('username', 'username');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
