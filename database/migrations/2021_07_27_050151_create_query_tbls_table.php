<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQueryTblsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('query_tbls', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id')->unique();
            $table->string('emp_id'); 
            $table->string('document');
            $table->string('remark')->nullable();
            // $table->string('admin_remark')->nullable();
            // $table->string('dec_remark')->nullable();
            // $table->string('approved_lvl')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('query_tbls');
    }
}
