<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRejectionReasonToTravelOrderAndLeaveTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('travel_order', function (Blueprint $table) {
            $table->text('rejected1_reason')->nullable()->after('is_rejected1');
            $table->text('rejected2_reason')->nullable()->after('is_rejected2');
            $table->text('rejected3_reason')->nullable()->after('is_rejected3');
        });

        Schema::table('leave', function (Blueprint $table) {
            $table->text('rejected1_reason')->nullable()->after('is_rejected1');
            $table->text('rejected2_reason')->nullable()->after('is_rejected2');
            $table->text('rejected3_reason')->nullable()->after('is_rejected3');
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
            $table->dropColumn(['rejected1_reason', 'rejected2_reason', 'rejected3_reason']);
        });

        Schema::table('leave', function (Blueprint $table) {
            $table->dropColumn(['rejected1_reason', 'rejected2_reason', 'rejected3_reason']);
        });
    }
}
