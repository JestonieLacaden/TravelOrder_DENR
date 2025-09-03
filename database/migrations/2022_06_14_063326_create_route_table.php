<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route', function (Blueprint $table) {
            $table->id();
            $table->date('actiondate');
            $table->string('documentid');
            $table->string('officeid');
            $table->string('sectionid');
            $table->string('unitid');
            $table->string('action');
            $table->boolean('is_active');
            $table->boolean('is_accepted')->default(false);
            $table->boolean('is_rejected')->default(false);
            $table->boolean('is_forwarded')->default(false);
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('route');
    }
}
