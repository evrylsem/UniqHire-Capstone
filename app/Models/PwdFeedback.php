<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PwdFeedback extends Model
{
    use HasFactory;
    protected $fillable = [
        'program_id',
        'pwd_id',
        'rating',
        'content'
    ];

    public function program()
    {
        return $this->belongsTo(TrainingProgram::class, 'program_id');
    }

    public function pwd()
    {
        return $this->belongsTo(User::class, 'pwd_id');
    }

    

}
