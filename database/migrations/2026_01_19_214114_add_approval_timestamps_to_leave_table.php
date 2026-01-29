<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalTimestampsToLeaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave', function (Blueprint $table) {
            // Add approval timestamp columns if they don't exist
            // Note: These are added after the is_approve columns since approve_by columns don't exist
            if (!Schema::hasColumn('leave', 'approve1_at')) {
                $table->timestamp('approve1_at')->nullable()->after('is_approve1')->comment('Approver 1 approval timestamp');
            }
            if (!Schema::hasColumn('leave', 'approve2_at')) {
                $table->timestamp('approve2_at')->nullable()->after('is_approve2')->comment('Approver 2 approval timestamp');
            }
            if (!Schema::hasColumn('leave', 'approve3_at')) {
                $table->timestamp('approve3_at')->nullable()->after('is_approve3')->comment('Approver 3 approval timestamp');
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
        Schema::table('leave', function (Blueprint $table) {
            if (Schema::hasColumn('leave', 'approve1_at')) {
                $table->dropColumn('approve1_at');
            }
            if (Schema::hasColumn('leave', 'approve2_at')) {
                $table->dropColumn('approve2_at');
            }
            if (Schema::hasColumn('leave', 'approve3_at')) {
                $table->dropColumn('approve3_at');
            }
        });
    }
}
