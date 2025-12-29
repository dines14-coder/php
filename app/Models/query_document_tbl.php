<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class query_document_tbl extends Model
{
    //
    protected $fillable = [ 
        'ticket_id', 
        'document', 
        'file_name', 
        'remark', 
        'dec_remark', 
        'status', 
        'updated_by',
    ];
}
