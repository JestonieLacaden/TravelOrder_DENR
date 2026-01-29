<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddReceivedActionToWorkflowHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE memorandum_workflow_history MODIFY COLUMN action ENUM('created','forwarded','returned','approved','revised','signed','received') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE memorandum_workflow_history MODIFY COLUMN action ENUM('created','forwarded','returned','approved','revised','signed') NOT NULL");
    }
}
