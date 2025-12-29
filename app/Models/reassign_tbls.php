<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class reassign_tbls extends Model
{
    //
     //
     protected $fillable = [ 
        'ticket_id', 
        'from_docu', 
        'to_docu',
        'updated_by', 
        'assign_from', 
        'assign_to',
    ];
}
