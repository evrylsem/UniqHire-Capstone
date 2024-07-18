<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'title',
        'description',
        'city',
        'participants',
        'start',
        'end',
        'disability_id',
        'education_id'
    ];

    public function agency() {
        return $this->belongsTo(User::class, 'agency_id');
    }

    public function disability()
    {
        return $this->belongsTo(Disability::class);
    }

    public function education()
    {
        return $this->belongsTo(EducationLevel::class, 'education_id');
    }

    public function crowdfund()
    {
        return $this->hasOne(CrowdfundEvent::class, 'program_id');
    }
}
