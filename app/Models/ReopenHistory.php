<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReopenHistory extends Model
{
    use HasFactory;
    protected $fillable = [ 
        'emp_id',
        'reopened_by',
        's_d_id'
    ];
}
