<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History_f_f extends Model
{
    use HasFactory;
    protected $table = 'history_f_f';
    protected $fillable = [
        'emp_id',
        'from_sg',
        'to_sg',
        'date',
        'time',
        'created_by',
        'sender_to',
        'created_at',
        'updated_at',
    ];
}
