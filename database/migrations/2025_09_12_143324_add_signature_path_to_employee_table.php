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
        Schema::table('employees', function (Blueprint $t) {
            if (!Schema::hasColumn('employees', 'signature_path')) {
                $t->string('signature_path')->nullable()->after('photo_path'); // or after any column
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
        Schema::table('employees', function (Blueprint $t) {
            if (Schema::hasColumn('employees', 'signature_path')) {
                $t->dropColumn('signature_path');
            }
        });
    }
};