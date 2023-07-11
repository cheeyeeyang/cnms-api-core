<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assigns', function (Blueprint $table) {
            $table->integer('AID')->autoIncrement();
            $table->integer('ZID');
            $table->integer('EMPID');
            $table->timestamps();
            $table->foreign('ZID')
            ->references('ZID')
            ->on('zones')
            ->onDelete('CASCADE');
            $table->foreign('EMPID')
            ->references('EMPID')
            ->on('employees')
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
        Schema::dropIfExists('assigns');
    }
}
