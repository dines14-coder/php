<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEscalateMailDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('escalate_mail_details', function (Blueprint $table) {
            $table->id();
            $table->string('to_sg');
            $table->string('to_mail');
            $table->string('level_one_cc');
            $table->string('level_two_cc');
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
        Schema::dropIfExists('escalate_mail_details');
    }
}
