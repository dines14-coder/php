<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevertTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revert_tables', function (Blueprint $table) {
            $table->id();
            $table->string('flow');
            $table->string('emp_id');
            $table->string('from_sg');
            $table->string('to_sg');
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
        Schema::dropIfExists('revert_tables');
    }
}
