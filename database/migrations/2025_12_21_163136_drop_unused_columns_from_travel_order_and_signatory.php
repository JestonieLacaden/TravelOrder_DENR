<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUnusedColumnsFromTravelOrderAndSignatory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop unused columns from travel_order table
        Schema::table('travel_order', function (Blueprint $table) {
            if (Schema::hasColumn('travel_order', 'start_date')) {
                $table->dropColumn('start_date');
            }
            if (Schema::hasColumn('travel_order', 'end_date')) {
                $table->dropColumn('end_date');
            }
        });

        // Drop unused column from set_travel_order_signatory table
        Schema::table('set_travel_order_signatory', function (Blueprint $table) {
            if (Schema::hasColumn('set_travel_order_signatory', 'signerid')) {
                $table->dropColumn('signerid');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Restore columns if migration is rolled back
        Schema::table('travel_order', function (Blueprint $table) {
            if (!Schema::hasColumn('travel_order', 'start_date')) {
                $table->date('start_date')->nullable()->after('daterange');
            }
            if (!Schema::hasColumn('travel_order', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
        });

        Schema::table('set_travel_order_signatory', function (Blueprint $table) {
            if (!Schema::hasColumn('set_travel_order_signatory', 'signerid')) {
                $table->string('signerid')->nullable()->after('travelordersignatoryid');
            }
        });
    }
}
