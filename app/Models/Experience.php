<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'date',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(UserInfo::class, 'user_id');
    }

}
