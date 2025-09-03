<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetTravelOrderSignatoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set_travel_order_signatory', function (Blueprint $table) {
            $table->id();
            $table->string('travelordersignatoryid');
            $table->string('sectionid');
            $table->string('officeid');
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
        Schema::dropIfExists('set_travel_order_signatory');
    }
}
