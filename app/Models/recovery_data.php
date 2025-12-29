<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class recovery_data extends Model
{
    protected $fillable = [ 
        'emp_id', 
        'r_id', 
        'values', 
        'remark', 
    ];
}
