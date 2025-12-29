<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';
    
    protected $fillable = [
        'emp_id',
        'name',
        'email',
        'created_at',
        'updated_at'
    ];
}