<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDtrSignatoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasTable('dtr_signatory')) {
            Schema::create('dtr_signatory', function (Blueprint $table) {
                $table->id();
                $table->string('employeeid');
                $table->string('signatory');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.  
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('dtr_signatory');
    }
}