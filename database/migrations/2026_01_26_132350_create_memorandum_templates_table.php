<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemorandumTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memorandum_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('version')->unique();
            $table->text('description')->nullable();

            // Layout Configuration (JSON)
            $table->json('layout_config'); // margins, fonts, font_sizes, spacing, etc.

            // Header Configuration
            $table->text('header_line_1')->default('Republic of the Philippines');
            $table->text('header_line_2')->default('Department of Environment and Natural Resources');
            $table->text('header_line_3')->default('Provincial Environment and Natural Resources Office');
            $table->text('header_line_4')->default('Occidental Mindoro');

            // Footer Configuration
            $table->text('footer_template')->nullable(); // Template for footer

            // Status
            $table->boolean('is_active')->default(false);
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('memorandum_templates');
    }
}
