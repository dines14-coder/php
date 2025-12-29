<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReassignTbls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reassign_tbls', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id');
            $table->string('from_docu');
            $table->string('to_docu');
            $table->string('created_by');
            $table->string('assign_from');
            $table->string('assign_to');
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
        Schema::dropIfExists('reassign_tbls');
    }
}
