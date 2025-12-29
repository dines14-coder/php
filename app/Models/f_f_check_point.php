<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class f_f_check_point extends Model
{
    //
    protected $fillable = [ 
        'emp_id', 
        'question_id', 
        'rating', 
        'remarks', 
        'qc_status', 
        'created_by', 
    ];
}
