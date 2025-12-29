<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StageGates extends Model
{
    protected $fillable=[
        'sg_id',
        'name',
        'tat_days',
        'role_id',
        'report_type',
        'position',
        'editor',
        'status',
    ];
}
