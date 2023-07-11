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
            $table->string('VILLNAME');	
            $table->string('DISNAME');	
            $table->string('PRONAME');	
            $table->string('WORK_PLACE')->nullable();	
            $table->date('BOD')->nullable();
            $table->text('NOTE')->nullable();
            $table->integer('ZID');
            $table->double('LAT');
            $table->double('LNG');	
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