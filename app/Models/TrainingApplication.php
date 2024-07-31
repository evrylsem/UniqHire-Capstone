<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'training_program_id',
        'application_status'     
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function program() {
        return $this->belongsTo(TrainingProgram::class, 'training_program_id');
    }

}
