<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSignaturePathsToLeaveSignatoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('leave_signatory', function (Blueprint $table) {
            $table->string('signature1_path')->nullable()->after('approver1');
            $table->string('signature2_path')->nullable()->after('approver2');
            $table->string('signature3_path')->nullable()->after('approver3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('leave_signatory', function (Blueprint $table) {
            $table->dropColumn(['signature1_path', 'signature2_path', 'signature3_path']);
        });
    }
}