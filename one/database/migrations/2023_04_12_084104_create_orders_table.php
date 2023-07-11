<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('ORID')->autoIncrement();
            $table->date('ORDATE');
            $table->integer('AMOUNT');
            $table->integer('UID');
            $table->integer('CID');
            $table->timestamps();
            $table->foreign('UID')
            ->references('UID')
            ->on('users')
            ->onDelete('CASCADE');
            $table->foreign('CID')
            ->references('CID')
            ->on('customers')
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
        Schema::dropIfExists('orders');
    }
}
