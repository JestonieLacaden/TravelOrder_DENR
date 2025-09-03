<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_order', function (Blueprint $table) {
            $table->id();
            $table->string('employeeid');
            $table->string('daterange');      
            $table->string('destinationoffice');
            $table->longtext('purpose');
            $table->string('perdime');
            $table->string('appropriation');
            $table->string('remarks');
            $table->string('userid');
            $table->boolean('is_approve1')->default(false);
            $table->boolean('is_approve2')->default(false);
            $table->boolean('is_rejected1')->default(false);
            $table->boolean('is_rejected2')->default(false);
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
        Schema::dropIfExists('travel_order');
    }
}
