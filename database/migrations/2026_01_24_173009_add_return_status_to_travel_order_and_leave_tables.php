<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReturnStatusToTravelOrderAndLeaveTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add return status columns to travel_order table
        Schema::table('travel_order', function (Blueprint $table) {
            $table->boolean('is_returned1')->default(false)->after('is_rejected3');
            $table->boolean('is_returned2')->default(false)->after('is_returned1');
            $table->boolean('is_returned3')->default(false)->after('is_returned2');
            $table->text('returned1_reason')->nullable()->after('rejected3_reason');
            $table->text('returned2_reason')->nullable()->after('returned1_reason');
            $table->text('returned3_reason')->nullable()->after('returned2_reason');
        });

        // Add return status columns to leave table
        Schema::table('leave', function (Blueprint $table) {
            $table->boolean('is_returned1')->default(false)->after('is_rejected3');
            $table->boolean('is_returned2')->default(false)->after('is_returned1');
            $table->boolean('is_returned3')->default(false)->after('is_returned2');
            $table->text('returned1_reason')->nullable()->after('rejected3_reason');
            $table->text('returned2_reason')->nullable()->after('returned1_reason');
            $table->text('returned3_reason')->nullable()->after('returned2_reason');
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
            $table->dropColumn(['is_returned1', 'is_returned2', 'is_returned3', 'returned1_reason', 'returned2_reason', 'returned3_reason']);
        });

        Schema::table('leave', function (Blueprint $table) {
            $table->dropColumn(['is_returned1', 'is_returned2', 'is_returned3', 'returned1_reason', 'returned2_reason', 'returned3_reason']);
        });
    }
}
