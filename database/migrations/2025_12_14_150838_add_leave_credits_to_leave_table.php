<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeaveCreditsToLeaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave', function (Blueprint $table) {
            $table->decimal('vacation_earned', 10, 3)->nullable()->default(0.000)->comment('Vacation leave total earned - edited by Approver1');
            $table->decimal('vacation_this_app', 10, 3)->nullable()->default(0.000)->comment('Vacation leave for this application');
            $table->decimal('vacation_balance', 10, 3)->nullable()->default(0.000)->comment('Vacation leave balance');
            $table->decimal('sick_earned', 10, 3)->nullable()->default(0.000)->comment('Sick leave total earned - edited by Approver1');
            $table->decimal('sick_this_app', 10, 3)->nullable()->default(0.000)->comment('Sick leave for this application');
            $table->decimal('sick_balance', 10, 3)->nullable()->default(0.000)->comment('Sick leave balance');
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
                'vacation_earned',
                'vacation_this_app',
                'vacation_balance',
                'sick_earned',
                'sick_this_app',
                'sick_balance',
            ]);
        });
    }
}
