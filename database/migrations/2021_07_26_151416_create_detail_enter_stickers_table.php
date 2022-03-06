<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailEnterStickersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_enter_stickers', function (Blueprint $table) {
            $table->unsignedInteger('idSticker');
            $table->unsignedInteger('idProduct');
            $table->integer('unit');
            $table->double('price');
            $table->primary(['idSticker', 'idProduct']);
            $table->timestamps();
        });

        Schema::table('detail_enter_stickers', function (Blueprint $table) {
            $table->foreign('idSticker')->references('id')->on('enter_stickers')->onDelete('cascade');
            $table->foreign('idProduct')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_enter_stickers');
    }
}
