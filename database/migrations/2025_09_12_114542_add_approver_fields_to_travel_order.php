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
        Schema::table('travel_order', function (Blueprint $t) {
            $t->unsignedBigInteger('approve1_by')->nullable()->after('is_approve1');
            $t->timestamp('approve1_at')->nullable()->after('approve1_by');

            $t->unsignedBigInteger('approve2_by')->nullable()->after('is_approve2');
            $t->timestamp('approve2_at')->nullable()->after('approve2_by');

            // optional FKs
            // $t->foreign('approve1_by')->references('id')->on('employee')->nullOnDelete();
            // $t->foreign('approve2_by')->references('id')->on('employee')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('travel_order', function (Blueprint $t) {
            // drop FKs first if you created them
            $t->dropColumn(['approve1_by', 'approve1_at', 'approve2_by', 'approve2_at']);
        });
    }
}