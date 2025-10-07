<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApproverFieldsToTravelOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('travel_order', function (Blueprint $table) {
            if (!Schema::hasColumn('travel_order', 'approve1_by')) {
                $table->unsignedBigInteger('approve1_by')->nullable()->after('is_approve1');
            }
            if (!Schema::hasColumn('travel_order', 'approve1_at')) {
                $table->timestamp('approve1_at')->nullable()->after('approve1_by');
            }
            if (!Schema::hasColumn('travel_order', 'approve2_by')) {
                $table->unsignedBigInteger('approve2_by')->nullable()->after('is_approve2');
            }
            if (!Schema::hasColumn('travel_order', 'approve2_at')) {
                $table->timestamp('approve2_at')->nullable()->after('approve2_by');
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
            if (Schema::hasColumn('travel_order', 'approve1_at')) {
                $table->dropColumn('approve1_at');
            }
            if (Schema::hasColumn('travel_order', 'approve1_by')) {
                $table->dropColumn('approve1_by');
            }
            if (Schema::hasColumn('travel_order', 'approve2_at')) {
                $table->dropColumn('approve2_at');
            }
            if (Schema::hasColumn('travel_order', 'approve2_by')) {
                $table->dropColumn('approve2_by');
            }
        });
    }
}