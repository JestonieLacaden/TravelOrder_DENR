<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee', function (Blueprint $table) {
            $table->id();
            $table->string('employeeid')->foreignkey()->unique();
            $table->string('firstname');
            $table->string('middlename');
            $table->string('lastname');
            $table->date('birthdate');
            $table->string('contactnumber');
            $table->string('email')->unique();
            $table->string('address');
            $table->string('officeid');
            $table->string('sectionid');
            $table->string('unitid');
            $table->string('position');
            $table->date('datehired');
            $table->string('empstatus');
            $table->string('officesectionunit');
            $table->boolean('has_account')->default(false);
            $table->string('picture')->nullable();
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
        Schema::dropIfExists('employee');
    }
}
