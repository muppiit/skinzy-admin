<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkinCondition extends Model
{
    use HasFactory;
    protected $primaryKey = 'condition_id';
    protected $fillable = [
        'condition_name',
        'description',
    ];

    public function recommendations()
    {
        return $this->hasMany(UserRecommendation::class);
    }
    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }
}
