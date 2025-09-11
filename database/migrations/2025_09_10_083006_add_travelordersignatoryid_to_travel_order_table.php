<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTravelordersignatoryidToTravelOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('travel_order', function (Blueprint $table) {
            Schema::table('travel_order', function (Blueprint $table) {
        // add nullable FK column after employeeid so old rows stay valid
        $table->foreignId('travelordersignatoryid')
              ->nullable()
              ->after('employeeid')
              ->constrained('travel_order_signatory') // references id on travel_order_signatory
              ->nullOnDelete();                       // if signatory is deleted, set NULL
    });
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
            Schema::table('travel_order', function (Blueprint $table) {
        // drop FK first, then the column
        $table->dropForeign(['travelordersignatoryid']); // FK name is auto: travel_order_travelordersignatoryid_foreign
        $table->dropColumn('travelordersignatoryid');
    });
        });
    }
}