<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class emp_profile_tbl extends Authenticatable
{

    use Notifiable;
    //
    protected $fillable = [ 
        'emp_id', 
        'emp_name', 
        'pan_no', 
        'dob', 
        'mobileno',
        'email',
        'type_of_leaving',
        'last_working_date',
        'address',
        'state',
        'city',
        'otp',
        'password',
        'is_first_login',
        'real_pass',
        'status',
        'doc_status',
        'revert_status',
        'ff_doc_updated_by',
        's_doc_updated_by',
        'remark',
        'remark_2',
        'f_f_document',

        'f_f_c_s_g',
        'cl_c_p',
        'fn_c_p',
        'pr_c_p',
        'hr_ld_c_p',
        'it_c_p',
        'it_inf_c_p',
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setpasswordAttribute( $password ) {
        $this->attributes['password'] = bcrypt( $password );
    }
}
