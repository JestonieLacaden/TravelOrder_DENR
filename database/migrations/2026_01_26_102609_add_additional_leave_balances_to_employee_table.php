<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalLeaveBalancesToEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee', function (Blueprint $table) {
            $table->decimal('force_leave_balance', 10, 3)->default(0.000)->after('sick_leave_balance');
            $table->decimal('special_privilege_leave_balance', 10, 3)->default(0.000)->after('force_leave_balance');
            $table->decimal('solo_parent_leave_balance', 10, 3)->default(0.000)->after('special_privilege_leave_balance');
            $table->boolean('solo_parent_eligible')->default(false)->after('solo_parent_leave_balance')->comment('Is employee eligible for solo parent leave');
            $table->decimal('wellness_leave_balance', 10, 3)->default(0.000)->after('solo_parent_eligible');
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
            $table->dropColumn([
                'force_leave_balance',
                'special_privilege_leave_balance',
                'solo_parent_leave_balance',
                'solo_parent_eligible',
                'wellness_leave_balance'
            ]);
        });
    }
}
