<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollee extends Model
{
    use HasFactory;

    protected $fillable = [
        'pwd_id',
        'program_id',
        'training_application_id',
        'completion_status'     
    ];

    public function application() {
        return $this->belongsTo(TrainingApplication::class, 'training_application_id');
    }

    public function program() {
        return $this->belongsTo(TrainingProgram::class, 'program_id');
    }

    public function pwd() {
        return $this->belongsTo(User::class, 'pwd_id');
    }
}
