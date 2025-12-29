<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFFTrackerAlumniDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f__f_tracker_alumni_datas', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id')->unique();
            $table->date('supervisor_clearance')->nullable();
            $table->date('c_admin_clearance')->nullable();
            $table->date('finanace_clearance')->nullable();
            $table->date('it_clearance')->nullable();
            $table->string('grade_set')->nullable();
            $table->string('grade')->nullable();
            $table->string('department')->nullable();
            $table->string('work_location')->nullable();
            $table->string('supervisor_name')->nullable();
            $table->string('reviewer_name')->nullable();
            $table->string('headquarters')->nullable();
            $table->string('hrbp_name')->nullable();
            $table->date('last_available_date')->nullable();
            $table->date('last_working_date')->nullable();
            $table->date('seperation_date')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->date('date_of_resignation')->nullable();

            $table->string('basic')->nullable();
            $table->string('da')->nullable();
            $table->string('other_allowance')->nullable();
            $table->string('hra')->nullable();
            $table->string('addl_hra')->nullable();
            $table->string('conveyance')->nullable();
            $table->string('lta')->nullable();
            $table->string('medical')->nullable();
            $table->string('spl_allowance')->nullable();
            $table->string('nps')->nullable();
            $table->string('super_annuation')->nullable();
            $table->string('sales_incentive')->nullable();
            $table->string('fixed_vehicle_allowance')->nullable();
            $table->string('gross')->nullable();

            $table->string('leave_balance_cl')->nullable();
            $table->string('leave_balance_pl')->nullable();
            $table->string('leave_balance_sl')->nullable();
            $table->string('is_probation_completed')->nullable();

            $table->string('sap_doc_no')->nullable();
            $table->string('posting_date')->nullable();
            $table->string('pay_rec')->nullable();
            $table->string('ff_amount')->nullable();
            $table->string('payout_amount')->nullable();
            $table->string('re_open_ct',10)->nullable();
            $table->string('re_opened_by',50)->nullable();

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
        Schema::dropIfExists('f__f_tracker_alumni_datas');
    }
}
