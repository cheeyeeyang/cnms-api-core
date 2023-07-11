<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotplansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notplans', function (Blueprint $table) {
            $table->integer('NPID')->autoIncrement();
            $table->date('NPDATE');
            $table->integer('UID');
            $table->integer('CID');
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
        Schema::dropIfExists('notplans');
    }
}
