<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class admin_tbl extends Model
{
    //
    protected $fillable = [ 
        'emp_id', 
        'emp_name', 
        'email',
        'password',
        'real_pass',
        'user_type',
        'head',
        'status',
    ];
}
