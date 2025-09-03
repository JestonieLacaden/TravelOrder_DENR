<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave', function (Blueprint $table) {
            $table->id();
            $table->string('daterange');
            $table->string('leaveid');
            $table->string('employeeid');
            $table->string('userid');
            $table->boolean('is_approve1')->default(false);
            $table->boolean('is_approve2')->default(false);
            $table->boolean('is_approve3')->default(false);
            $table->boolean('is_rejected1')->default(false);
            $table->boolean('is_rejected2')->default(false);
            $table->boolean('is_rejected3')->default(false);
            $table->string('yearapplied');
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
        Schema::dropIfExists('leave');
    }
}
