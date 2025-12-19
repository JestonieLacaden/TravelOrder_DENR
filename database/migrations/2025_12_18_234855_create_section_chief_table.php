<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionChiefTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create section_chief table
        Schema::create('section_chief', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unitid')->unique()->comment('One chief per unit - UNIQUE constraint');
            $table->unsignedBigInteger('employeeid')->comment('Employee who is the Section Chief');
            $table->timestamps();

            // Foreign keys
            $table->foreign('unitid')->references('id')->on('unit')->onDelete('cascade');
            $table->foreign('employeeid')->references('id')->on('employee')->onDelete('cascade');

            // Indexes for faster queries
            $table->index('unitid');
            $table->index('employeeid');
        });

        // Add approver3 column to travel_order_signatory table
        Schema::table('travel_order_signatory', function (Blueprint $table) {
            if (!Schema::hasColumn('travel_order_signatory', 'approver3')) {
                $table->unsignedBigInteger('approver3')->nullable()->after('approver2')->comment('PENRO - Final Approver');
                $table->foreign('approver3')->references('id')->on('employee')->onDelete('set null');
            }
        });

        // Add 3rd level approval columns to travel_order table
        Schema::table('travel_order', function (Blueprint $table) {
            if (!Schema::hasColumn('travel_order', 'is_approve3')) {
                $table->boolean('is_approve3')->default(false)->after('is_approve2')->comment('PENRO approval status');
            }
            if (!Schema::hasColumn('travel_order', 'is_rejected3')) {
                $table->boolean('is_rejected3')->default(false)->after('is_rejected2')->comment('PENRO rejection status');
            }
            if (!Schema::hasColumn('travel_order', 'approve3_by')) {
                $table->unsignedBigInteger('approve3_by')->nullable()->after('approve2_by')->comment('PENRO employee ID');
                $table->foreign('approve3_by')->references('id')->on('employee')->onDelete('set null');
            }
            if (!Schema::hasColumn('travel_order', 'approve3_at')) {
                $table->timestamp('approve3_at')->nullable()->after('approve2_at')->comment('PENRO approval timestamp');
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
        // Drop section_chief table
        Schema::dropIfExists('section_chief');

        // Remove approver3 from travel_order_signatory
        Schema::table('travel_order_signatory', function (Blueprint $table) {
            if (Schema::hasColumn('travel_order_signatory', 'approver3')) {
                $table->dropForeign(['approver3']);
                $table->dropColumn('approver3');
            }
        });

        // Remove 3rd level approval columns from travel_order
        Schema::table('travel_order', function (Blueprint $table) {
            if (Schema::hasColumn('travel_order', 'approve3_by')) {
                $table->dropForeign(['approve3_by']);
            }
            $table->dropColumn(['is_approve3', 'is_rejected3', 'approve3_by', 'approve3_at']);
        });
    }
}
