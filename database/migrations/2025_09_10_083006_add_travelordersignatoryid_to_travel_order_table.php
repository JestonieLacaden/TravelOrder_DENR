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
    public function up(): void
    {
        Schema::table('travel_order', function (Blueprint $table) {
            if (!Schema::hasColumn('travel_order', 'travelordersignatoryid')) {
                $table->unsignedBigInteger('travelordersignatoryid')
                    ->nullable()
                    ->after('employeeid');

                // NOTE: Kung gusto mong maglagay ng real FK, gawin mo ito:
                // $table->foreign('travelordersignatoryid')
                //       ->references('id')->on('travel_order_signatory')
                //       ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('travel_order', function (Blueprint $table) {
            if (Schema::hasColumn('travel_order', 'travelordersignatoryid')) {

                // Kung NAGLAGAY ka ng real FK sa up():
                // try {
                //     $table->dropConstrainedForeignId('travelordersignatoryid');
                // } catch (\Throwable $e) {
                //     // ignore kung wala talagang FK
                // }

                // Safe kahit walang FK:
                $table->dropColumn('travelordersignatoryid');
            }
        });
    }
}