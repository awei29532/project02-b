<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_data', function (Blueprint $table) {
            $table->tinyInteger('site_id')->comment('站台ID')->primary();
            $table->string('title', 200)->comment('網站title');
            $table->string('logo', 50);
            $table->text('meta');
            $table->text('keyword');
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
        Schema::dropIfExists('site_data');
    }
}
