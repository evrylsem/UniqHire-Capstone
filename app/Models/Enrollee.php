<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollee extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_application_id',
        'completion_status'     
    ];

    public function application() {
        return $this->belongsTo(TrainingApplication::class, 'training_id');
    }
}
