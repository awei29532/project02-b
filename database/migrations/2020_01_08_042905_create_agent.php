<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('site_id', false, true);
            $table->integer('user_id', false, true);
            $table->integer('level', false, true);
            $table->integer('rebate_id', false, true)->comment('返水')->nullable();
            $table->string('cell_phone', 50)->nullable();
            $table->string('invitation_code', 6)->comment('推廣代碼');
            $table->string('remark', 100)->nullable();
            $table->timestamps();

            $table->unique('cell_phone', 'cell_phone');
            $table->unique('invitation_code', 'invitation_code');
            $table->nestedSet();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent');
    }
}
