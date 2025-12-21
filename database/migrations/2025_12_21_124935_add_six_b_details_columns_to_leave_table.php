<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSixBDetailsColumnsToLeaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave', function (Blueprint $table) {
            // Only add the missing 6.B Details columns (others already exist)
            $table->boolean('study_masters_degree')->default(0)->after('outpatient_specify');
            $table->boolean('study_bar_board')->default(0)->after('study_masters_degree');
            $table->boolean('other_monetization')->default(0)->after('study_bar_board');
            $table->boolean('other_terminal_leave')->default(0)->after('other_monetization');
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
                'study_masters_degree',
                'study_bar_board',
                'other_monetization',
                'other_terminal_leave'
            ]);
        });
    }
}