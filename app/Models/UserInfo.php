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
        return $this->belongsTo(Disability::class);
    }

    public function education()
    {
        return $this->belongsTo(EducationLevel::class);
    }
}
