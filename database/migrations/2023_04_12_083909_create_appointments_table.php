<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->integer('APID')->autoIncrement();
            $table->date('APDATE');
            $table->enum('TRACK', ['YES', 'NO']);
            $table->integer('PID');
            $table->integer('UID');
            $table->timestamps();
            $table->foreign('PID')
            ->references('PID')
            ->on('plans')
            ->onDelete('CASCADE');
            $table->foreign('UID')
            ->references('UID')
            ->on('users')
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
        Schema::dropIfExists('appointments');
    }
}
