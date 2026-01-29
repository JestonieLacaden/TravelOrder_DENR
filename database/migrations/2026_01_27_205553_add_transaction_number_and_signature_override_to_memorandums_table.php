<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactionNumberAndSignatureOverrideToMemorandumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memorandums', function (Blueprint $table) {
            $table->string('transaction_number')->nullable()->after('memorandum_number');
            $table->string('signature_override_path')->nullable()->after('use_esignature');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memorandums', function (Blueprint $table) {
            $table->dropColumn(['transaction_number', 'signature_override_path']);
        });
    }
}
