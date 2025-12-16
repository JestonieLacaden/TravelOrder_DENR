<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudyLeaveFieldsToLeaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave', function (Blueprint $table) {
            // 6.B Study Leave checkboxes
            $table->boolean('study_masters_degree')->nullable()->default(false)->comment('Completion of Master\'s Degree');
            $table->boolean('study_bar_board')->nullable()->default(false)->comment('BAR/Board Examination Review');

            // 6.B Other Purpose checkboxes
            $table->boolean('other_monetization')->nullable()->default(false)->comment('Monetization of Leave Credits');
            $table->boolean('other_terminal_leave')->nullable()->default(false)->comment('Terminal Leave');
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
                'other_terminal_leave',
            ]);
        });
    }
}
