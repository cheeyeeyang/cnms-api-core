<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->integer('ODID')->autoIncrement();
            $table->integer('ORID');
            $table->integer('QTY');
            $table->integer('UID');
            $table->integer('PRICE');
            $table->integer('PDID');
            $table->timestamps();
            $table->foreign('ORID')
            ->references('ORID')                  
            ->on('orders')
            ->onDelete('CASCADE');
            $table->foreign('UID')
            ->references('UID')
            ->on('users')
            ->onDelete('CASCADE');
            $table->foreign('PDID')
            ->references('PDID')
            ->on('products')
            ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
