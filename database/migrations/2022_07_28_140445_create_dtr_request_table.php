<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDtrRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtr_request', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('employeeid');
            $table->string('schedule');
            $table->time('time');
            $table->string('remarks')->nullable();
            $table->boolean('isapproved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dtr_request');
    }
}
