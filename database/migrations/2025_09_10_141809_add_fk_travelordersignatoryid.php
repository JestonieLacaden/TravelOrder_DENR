<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkTravelordersignatoryid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('travel_order', function (Blueprint $table) {
            $table->foreign('travelordersignatoryid')
          ->references('id')->on('travel_order_signatory')
          ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('travel_order', function (Blueprint $table) {
            //
        });
    }
}