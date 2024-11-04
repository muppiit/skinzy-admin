<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;
    protected $fillable = [
        'username',
        'email',
        'password',
        'profile_image',
        'gender',
        'age',
        'level',
    ];
    
    public function userHistories()
    {
        return $this->hasMany(UserHistory::class);
    }
    
    public function recommendations()
    {
        return $this->hasMany(UserRecommendation::class);
    }
}
