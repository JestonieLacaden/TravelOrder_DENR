<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingEntryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_entry', function (Blueprint $table) {
            $table->id();
            $table->string('fmid');
            $table->string('activity_id');
            $table->string('uacs_id');
            $table->float('debit')->nullable();
            $table->float('credit')->nullable();
            $table->string('userid');
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
        Schema::dropIfExists('accounting_entry');
    }
}
