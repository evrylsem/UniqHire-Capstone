<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competency extends Model
{

    protected $fillable = [
        'name',
    ];

    public function trainingPrograms()
    {
        return $this->belongsToMany(TrainingProgram::class, 'program_competency');
    }

    use HasFactory;
}
