<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('UID')->autoIncrement();
            $table->string('USERNAME', 50);
            $table->string('password');
            $table->tinyInteger('TYPE')->length(2);
            $table->integer('EMPID');
            $table->timestamps();
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
        Schema::dropIfExists('users');
    }
}
