<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questions_table extends Model
{
    protected $table = ['questions_table'];
    protected $fillable = [ 
        'question_id', 
        'role_id', 
        'questions', 

    ];
}
