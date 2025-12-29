<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class query_tbl extends Model
{
    //
    protected $fillable = [ 
        'ticket_id', 
        'emp_id', 
        'document', 
        'remark', 
        // 'admin_remark', 
        // 'dec_remark', 
        // 'approved_lvl', 
        'updated_by', 
        'status', 
    ];
}
