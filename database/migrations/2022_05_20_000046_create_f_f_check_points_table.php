<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFFCheckPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f_f_check_points', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('question_id');
            $table->string('rating')->nullable();
            $table->string('remarks')->nullable();
            $table->string('qc_status')->nullable();
            $table->string('created_by');
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
        Schema::dropIfExists('f_f_check_points');
    }
}
