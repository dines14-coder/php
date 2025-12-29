<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id')->unique();
            $table->string('employee_name')->nullable();
            $table->string('pan_no')->nullable();
            $table->date('dob')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('status')->default('Inactive');
            $table->string('org_code')->default('ORG00001');
            $table->timestamp('last_synced_at')->nullable();
            $table->json('api_data')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
}