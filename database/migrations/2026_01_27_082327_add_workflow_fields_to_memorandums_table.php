<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkflowFieldsToMemorandumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memorandums', function (Blueprint $table) {
            // Add new statuses to the enum (will need to alter the column)
            // Current holder who can take action
            $table->integer('current_holder_id')->nullable()->after('created_by');
            // Previous holder (for return flow)
            $table->integer('previous_holder_id')->nullable()->after('current_holder_id');
            // Revision counter
            $table->integer('revision_count')->default(0)->after('previous_holder_id');
            // Approved at timestamp
            $table->timestamp('approved_at')->nullable()->after('generated_at');
            // Returned at timestamp
            $table->timestamp('returned_at')->nullable()->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memorandums', function (Blueprint $table) {
            $table->dropColumn(['current_holder_id', 'previous_holder_id', 'revision_count', 'approved_at', 'returned_at']);
        });
    }
}
