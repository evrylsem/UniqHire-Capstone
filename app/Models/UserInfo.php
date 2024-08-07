<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'disability_id',
        'educational_id',
        'name',
        'contactnumber',
        'state',
        'city',
        'pwd_card',
        'age',
        'about',
        'founder',
        'year_established',
        'affiliations',
        'awards'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function disability()
    {
        return $this->belongsTo(Disability::class, 'disability_id');
    }

    public function education()
    {
        return $this->belongsTo(EducationLevel::class, 'educational_id');
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class, 'user_id');
    }

    public function skills()
    {
        return $this->hasMany(SkillUser::class, 'user_id');
    }
}
