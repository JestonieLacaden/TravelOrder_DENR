<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSignaturePathToEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('employee', function (Blueprint $t) {
            if (!Schema::hasColumn('employee', 'signature_path')) {
                $t->string('signature_path')->nullable()->after('position');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('employee', function (Blueprint $t) {
            if (Schema::hasColumn('employee', 'signature_path')) {
                $t->dropColumn('signature_path');
            }
        });
    }
}