<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveCreditsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_credits_history', function (Blueprint $table) {
            $table->id();

            // Employee reference
            $table->unsignedBigInteger('employeeid');

            // Period (Year-Month)
            $table->integer('period_year'); // e.g., 2024, 2025
            $table->integer('period_month'); // 1-12 (January=1, December=12)

            // Monthly earned credits
            $table->decimal('vacation_earned', 10, 3)->default(0.000);
            $table->decimal('sick_earned', 10, 3)->default(0.000);

            // Running balance (optional - can be computed)
            $table->decimal('vacation_balance', 10, 3)->nullable();
            $table->decimal('sick_balance', 10, 3)->nullable();

            // Import tracking
            $table->unsignedBigInteger('imported_by')->nullable();
            $table->timestamp('imported_at')->nullable();

            $table->timestamps();

            // Indexes and constraints
            $table->unique(['employeeid', 'period_year', 'period_month'], 'unique_employee_period');
            $table->foreign('employeeid')->references('id')->on('employee')->onDelete('cascade');
            $table->foreign('imported_by')->references('id')->on('users')->onDelete('set null');

            // Index for fast queries
            $table->index(['employeeid', 'period_year']);
            $table->index('period_year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_credits_history');
    }
}
