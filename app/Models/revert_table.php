<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class revert_table extends Model
{
    protected $fillable=[
        'flow',
        'emp_id',
        'from_sg',
        'to_sg',
        'remark',
        'created_by',
    ];
}
