<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skill_id',
    ];

    public function user()
    {
        return $this->belongsTo(UserInfo::class, 'user_id');
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }
}
