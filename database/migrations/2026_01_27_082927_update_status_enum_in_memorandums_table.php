<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateStatusEnumInMemorandumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update status enum to include new workflow statuses
        DB::statement("ALTER TABLE memorandums MODIFY COLUMN status ENUM('Draft', 'For Review', 'Returned', 'Approved', 'Previewed', 'Generated', 'Forwarded', 'Signed') DEFAULT 'Draft'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert to original enum
        DB::statement("ALTER TABLE memorandums MODIFY COLUMN status ENUM('Draft', 'Previewed', 'Generated', 'Forwarded', 'Signed') DEFAULT 'Draft'");
    }
}
