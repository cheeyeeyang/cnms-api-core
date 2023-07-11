<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->integer('PID')->autoIncrement();
            $table->integer('UID');
            $table->integer('CID');
            $table->decimal('TARGET');
            $table->decimal('ACTUAL');
            $table->decimal('PERCENTAGE');
            $table->text('LOCATION');
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
        Schema::dropIfExists('plans');
    }
}
