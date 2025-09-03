<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFmOrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fm_ors', function (Blueprint $table) {
            $table->id();
            $table->string('fc');
            $table->string('fmid');
            $table->string('particulars');
            $table->string('orsno');
            $table->float('obligation');
            $table->float('payable');
            $table->float('payment');
            $table->float('dd');
            $table->float('nyd');
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
        Schema::dropIfExists('fm_ors');
    }
}
