<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateExistingLeaveTimestamps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update existing approved leaves with timestamps based on updated_at
        // This is a one-time data migration for historical records

        DB::table('leave')
            ->where('is_approve1', true)
            ->whereNull('approve1_at')
            ->update(['approve1_at' => DB::raw('updated_at')]);

        DB::table('leave')
            ->where('is_approve2', true)
            ->whereNull('approve2_at')
            ->update(['approve2_at' => DB::raw('updated_at')]);

        DB::table('leave')
            ->where('is_approve3', true)
            ->whereNull('approve3_at')
            ->update(['approve3_at' => DB::raw('updated_at')]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No rollback needed - this is a data migration
        // If needed, we could set these back to NULL
    }
}
