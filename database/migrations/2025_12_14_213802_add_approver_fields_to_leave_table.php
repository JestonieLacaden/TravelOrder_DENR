<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApproverFieldsToLeaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave', function (Blueprint $table) {
            // 6.B Details of Leave
            $table->string('location_within_ph', 255)->nullable()->comment('Within Philippines location');
            $table->string('location_abroad', 255)->nullable()->comment('Abroad location');
            $table->string('hospital_specify', 255)->nullable()->comment('In Hospital - illness');
            $table->string('outpatient_specify', 255)->nullable()->comment('Out Patient - illness');

            // 6.D Commutation
            $table->enum('commutation', ['not_requested', 'requested'])->nullable()->comment('Commutation status');

            // 7.B Recommendation
            $table->enum('recommendation', ['for_approval', 'for_disapproval'])->nullable()->comment('Approver recommendation');
            $table->text('recommendation_notes')->nullable()->comment('Recommendation details/reasons');

            // 7.C Approved For
            $table->integer('days_with_pay')->nullable()->default(0)->comment('Days approved with pay');
            $table->integer('days_without_pay')->nullable()->default(0)->comment('Days approved without pay');
            $table->string('approved_others', 255)->nullable()->comment('Others (Specify)');

            // 7.D Disapproved Due To
            $table->text('disapproved_reason')->nullable()->comment('Disapproval reasons');
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
            $table->dropColumn([
                'location_within_ph',
                'location_abroad',
                'hospital_specify',
                'outpatient_specify',
                'commutation',
                'recommendation',
                'recommendation_notes',
                'days_with_pay',
                'days_without_pay',
                'approved_others',
                'disapproved_reason',
            ]);
        });
    }
}
