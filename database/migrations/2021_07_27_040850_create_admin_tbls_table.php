<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTblsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_tbls', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id')->unique();
            $table->string('emp_name'); 
            $table->string('email')->unique();
            $table->string('password');
            $table->string('real_pass');
            $table->string('department');
            $table->string('user_type');
            $table->string('head');
            $table->string('status');
            $table->rememberToken();
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
        Schema::dropIfExists('admin_tbls');
    }
}
