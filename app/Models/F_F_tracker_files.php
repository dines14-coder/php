<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class F_F_tracker_files extends Model
{
    //
    protected $fillable = [ 
        'flow', 
        'emp_id', 
        's_g_id',
        'doc_type',
        'filename',
        'remark',
        'created_by',
    ];
}
