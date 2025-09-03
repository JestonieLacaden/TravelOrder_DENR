<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinMgmtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fin_mgmt', function (Blueprint $table) {
            $table->id();
            $table->date('datereceived');
            $table->string('sequenceid')->foreignkey()->unique();
            $table->string('acct_id');
            $table->string('acct_no');
            $table->string('office');
            $table->string('address');
            $table->string('particulars');
            $table->string('remarks')->nullable();;
            $table->float('amount');
            // $table->string('orsnumber')->nullable();
            // $table->string('accountnumber')->nullable();
            // $table->string('adanumber')->nullable();
            // $table->string('lddapnumber')->nullable();
            // $table->boolean('is_doc_complete')->default(false);
            $table->string('certified_by');
            $table->string('signatory_id')->nullable();;
            $table->string('userid');
            $table->string('userunitid');
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
        Schema::dropIfExists('fin_mgmt');
    }
}
