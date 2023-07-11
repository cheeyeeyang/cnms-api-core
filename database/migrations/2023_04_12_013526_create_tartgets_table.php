<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTartgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tartgets', function (Blueprint $table) {
            $table->integer('TGID')->autoIncrement();
            $table->integer('UID');
            $table->biginteger('TARGET');
            $table->biginteger('AMOUNT')->length(2);
            $table->decimal('PERCENTAGE');
            $table->timestamps();
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
        Schema::dropIfExists('tartgets');
    }
}
