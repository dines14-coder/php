<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class F_F_tracker_alumni_data extends Model
{
    protected $fillable = [ 
        'emp_id', 
        'supervisor_clearance', 
        'c_admin_clearance', 
        'finanace_clearance', 
        'it_clearance', 
        'grade_set', 
        'grade', 
        'department', 
        'work_location', 
        'supervisor_name', 
        'reviewer_name', 
        'headquarters', 
        'hrbp_name', 
        'last_working_date', 
        'seperation_date', 
        'date_of_joining', 
        'date_of_resignation', 

        'basic',
        'da',
        'other_allowance',
        'hra',
        'addl_hra',
        'conveyance',
        'lta',
        'medical',
        'spl_allowance',
        'nps',
        'super_annuation',
        'fixed_stipend',
        'sales_incentive',
        'fixed_vehicle_allowance',
        'gross',

        'leave_balance_cl',
        'leave_balance_pl',
        'leave_balance_sl',
        'is_probation_completed',
        'sap_doc_no',
        'posting_date',
        'pay_rec',
        'ff_amount',
        'payout_amount',

        're_open_ct',
        're_opened_by',
        'created_by', 
    ];
}
