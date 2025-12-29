<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank_detail_account extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id',
        'cheque',
        'passbook',
        'status',
        ''
        ];
}
