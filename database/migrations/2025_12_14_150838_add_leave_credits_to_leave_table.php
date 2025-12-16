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
            $table->integer('vacation_earned')->nullable()->default(0)->comment('Vacation leave total earned - edited by Approver1');
            $table->integer('vacation_this_app')->nullable()->default(0)->comment('Vacation leave for this application');
            $table->integer('vacation_balance')->nullable()->default(0)->comment('Vacation leave balance');
            $table->integer('sick_earned')->nullable()->default(0)->comment('Sick leave total earned - edited by Approver1');
            $table->integer('sick_this_app')->nullable()->default(0)->comment('Sick leave for this application');
            $table->integer('sick_balance')->nullable()->default(0)->comment('Sick leave balance');
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
