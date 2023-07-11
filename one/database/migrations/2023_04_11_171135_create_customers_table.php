<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->integer('CID')->autoIncrement();
            $table->string('CNAME');
            $table->string('TEL',12);
            $table->text('LOCATION');
            $table->integer('ZID');
            $table->timestamps();
            $table->foreign('ZID')
            ->references('ZID')
            ->on('zones')
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
        Schema::dropIfExists('customers');
    }
}