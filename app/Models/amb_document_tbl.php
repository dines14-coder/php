<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class amb_document_tbl extends Model
{
    //
    protected $fillable = [ 
        'emp_id', 
        'document', 
        'file_name', 
        'status', 
    ];
}
