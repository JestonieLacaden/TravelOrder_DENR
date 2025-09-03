<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document', function (Blueprint $table) {
            $table->id();
            $table->string('PDN')->foreignkey()->unique();
            $table->string('originatingoffice');
            $table->string('sendername');
            $table->string('senderaddress');
            $table->date('datereceived');
            $table->string('addressee');
            $table->longText('subject');
            $table->string('doc_type');
            $table->boolean('is_urgent');
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
        Schema::dropIfExists('document');
    }
}
