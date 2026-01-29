<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemorandumWorkflowHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memorandum_workflow_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memorandum_id')->constrained('memorandums')->onDelete('cascade');
            $table->integer('from_user_id'); // Who took the action
            $table->integer('to_user_id')->nullable(); // Who received (for forward)
            $table->enum('action', ['created', 'forwarded', 'returned', 'approved', 'revised', 'signed']);
            $table->string('previous_status')->nullable();
            $table->string('new_status');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memorandum_workflow_history');
    }
}
