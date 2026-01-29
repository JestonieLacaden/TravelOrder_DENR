<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemorandumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memorandums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('memorandum_templates');

            // Document Info
            $table->date('memorandum_date');
            $table->string('memorandum_number')->nullable(); // Auto-generated or manual

            // Recipients
            $table->enum('recipient_type', ['FOR', 'TO', 'BOTH']);
            $table->json('recipient_for')->nullable(); // Array of user IDs or positions
            $table->json('recipient_to')->nullable(); // Array of user IDs or positions

            // Sender
            $table->integer('from_user_id');
            $table->string('from_name')->nullable(); // Override if needed
            $table->string('from_position')->nullable();

            // Content
            $table->string('subject');
            $table->longText('body'); // Multiple paragraphs separated by \n

            // Signature
            $table->boolean('use_esignature')->default(false);
            $table->string('signature_path')->nullable();

            // Status & Tracking
            $table->enum('status', ['Draft', 'Previewed', 'Generated', 'Forwarded', 'Signed'])->default('Draft');
            $table->integer('created_by');
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('signed_at')->nullable();

            // Generated Files
            $table->string('docx_path')->nullable();
            $table->string('pdf_path')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memorandums');
    }
}
