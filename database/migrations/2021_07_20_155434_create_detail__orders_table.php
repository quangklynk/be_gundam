<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail__orders', function (Blueprint $table) {
            $table->unsignedInteger('idOrder');
            $table->unsignedInteger('idProduct');
            $table->integer('unit');
            $table->double('price');
            $table->primary(['idOrder', 'idProduct']);
        });

        Schema::table('detail__orders', function (Blueprint $table) {
            $table->foreign('idOrder')->references('id')->on('orders')->onDelete('cascade');
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
        Schema::dropIfExists('detail__orders');
    }
}
