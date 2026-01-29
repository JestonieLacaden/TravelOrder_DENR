<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MakeApprover2NullableInTravelOrderSignatoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Make approver2 nullable
        DB::statement('ALTER TABLE `travel_order_signatory` MODIFY `approver2` VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert approver2 to NOT NULL
        DB::statement('ALTER TABLE `travel_order_signatory` MODIFY `approver2` VARCHAR(255) NOT NULL');
    }
}
