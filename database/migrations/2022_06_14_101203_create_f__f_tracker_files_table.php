<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFFTrackerFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f__f_tracker_files', function (Blueprint $table) {
            $table->id();
            $table->string('flow');
            $table->string('emp_id')->nullable();
            $table->string('s_g_id')->nullable();
            $table->string('doc_type')->nullable();
            $table->string('filename')->nullable();
            $table->text('remark')->nullable();
            $table->string('created_by')->nullable();
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
        Schema::dropIfExists('f__f_tracker_files');
    }
}
