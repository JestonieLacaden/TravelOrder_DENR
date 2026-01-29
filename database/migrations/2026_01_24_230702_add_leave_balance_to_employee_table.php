<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeaveBalanceToEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee', function (Blueprint $table) {
            $table->decimal('vacation_leave_balance', 10, 3)->default(0.000)->after('has_account');
            $table->decimal('sick_leave_balance', 10, 3)->default(0.000)->after('vacation_leave_balance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee', function (Blueprint $table) {
            $table->dropColumn(['vacation_leave_balance', 'sick_leave_balance']);
        });
    }
}
