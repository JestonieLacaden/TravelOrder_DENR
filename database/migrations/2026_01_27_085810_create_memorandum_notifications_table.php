<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemorandumNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memorandum_notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id'); // Recipient of notification
            $table->foreignId('memorandum_id')->constrained('memorandums')->onDelete('cascade');
            $table->integer('actor_id')->nullable(); // Who performed the action
            $table->enum('type', ['forwarded', 'returned', 'approved', 'commented', 'finalized', 'revised']);
            $table->string('title');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memorandum_notifications');
    }
}
