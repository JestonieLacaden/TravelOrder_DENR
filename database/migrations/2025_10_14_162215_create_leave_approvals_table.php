<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('leave_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('step'); // 1, 2, 3
            $table->foreignId('approver_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('approver_name');
            $table->string('approver_position')->nullable();
            $table->string('signature_path')->nullable(); // path under storage/app/public
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->unique(['leave_id', 'step']); // snapshot per step is unique
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_approvals');
    }
}