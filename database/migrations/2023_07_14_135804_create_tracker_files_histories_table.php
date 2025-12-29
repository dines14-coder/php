<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackerFilesHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracker_files_histories', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('flow');
            $table->string('s_g_id');
            $table->string('doc_type');
            $table->string('filename');
            $table->string('remark');
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
        Schema::dropIfExists('tracker_files_histoties');
    }
}
