<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpProfileTblsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emp_profile_tbls', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id')->unique();
            $table->string('emp_name'); 
            $table->string('pan_no')->nullable()->unique();
            $table->date('dob');
            $table->string('mobileno')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->string('address');
            $table->string('state');
            $table->string('city');
            $table->string('otp');
            $table->string('password');
            $table->string('real_pass');
            $table->string('status');
            $table->string('doc_status')->nullable();
            $table->string('revert_status')->nullable();
            $table->string('ff_doc_updated_by')->nullable();
            $table->string('s_doc_updated_by')->nullable();
            $table->text('remark')->nullable();
            $table->text('remark_2')->nullable();
            $table->string('f_f_document')->default('no');
            $table->string('f_f_c_s_g')->nullable();
            $table->string('cl_c_p')->nullable();
            $table->string('fn_c_p')->nullable();
            $table->string('pr_c_p')->nullable();
            $table->string('hr_ld_c_p')->nullable();
            $table->string('it_c_p')->nullable();
            $table->string('it_inf_c_p')->nullable(); 
            $table->string('type_of_leaving');
            $table->date('last_working_date');
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
        Schema::dropIfExists('emp_profile_tbls');
    }
}
