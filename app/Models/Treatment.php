<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_treatment';
    protected $fillable = [
        'deskripsi_treatment',
    ];

    public function recommendations()
    {
        return $this->hasMany(UserRecommendation::class);
    }
    public function skinCondition()
    {
        return $this->hasMany(related: SkinCondition::class);
    }
}
