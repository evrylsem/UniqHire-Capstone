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
        'state',
        'city',
        'participants',
        'start',
        'end',
        'disability_id',
        'education_id',
        'start_age',
        'end_age',
        'skill_id',
    ];

    public function agency()
    {
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

    public function feedbacks()
    {
        return $this->hasMany(PwdFeedback::class, 'program_id');
    }

    public function competencies()
    {
        return $this->belongsToMany(Competency::class, 'program_competency', 'training_program_id', 'competency_id');
    }

    public function enrollees()
    {
        return $this->hasMany(Enrollee::class, 'program_id');
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }
}
