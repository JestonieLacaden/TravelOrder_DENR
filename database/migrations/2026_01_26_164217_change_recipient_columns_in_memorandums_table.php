<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRecipientColumnsInMemorandumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE memorandums MODIFY COLUMN recipient_for TEXT NULL');
        DB::statement('ALTER TABLE memorandums MODIFY COLUMN recipient_to TEXT NULL');
        DB::statement('ALTER TABLE memorandums ADD COLUMN attn VARCHAR(255) NULL AFTER recipient_to');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE memorandums DROP COLUMN attn');
        DB::statement('ALTER TABLE memorandums MODIFY COLUMN recipient_for JSON NULL');
        DB::statement('ALTER TABLE memorandums MODIFY COLUMN recipient_to JSON NULL');
    }
}
