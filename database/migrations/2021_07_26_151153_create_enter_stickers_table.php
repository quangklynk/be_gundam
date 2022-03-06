<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnterStickersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enter_stickers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idEmployee');
            $table->boolean('flag');
            $table->timestamps();
        });
        Schema::table('enter_stickers', function (Blueprint $table) {
            $table->foreign('idEmployee')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enter_stickers');
    }
}
