<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRequestIdToTravelOrderApprovedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('travel_order_approved', function (Blueprint $t) {
            if (!Schema::hasColumn('travel_order_approved', 'request_id')) {
                $t->unsignedBigInteger('request_id')->nullable()->unique()->after('id');
            }
            // helpful indexes
            if (Schema::hasColumn('travel_order_approved', 'created_at')) {
                $t->index('created_at');
            }
            if (
                !Schema::hasColumn('travel_order_approved', 'employeeid') &&
                Schema::hasColumn('travel_order_approved', 'employeeeid')
            ) {
                $t->index('employeeeid');
            } else {
                $t->index('employeeid');
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
        Schema::table('travel_order_approved', function (Blueprint $t) {
            if (Schema::hasColumn('travel_order_approved', 'request_id')) {
                $t->dropUnique(['request_id']);
                $t->dropColumn('request_id');
            }
        });
    }
}