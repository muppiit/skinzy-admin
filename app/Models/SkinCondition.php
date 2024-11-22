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
        'id_treatment', // Kolom baru untuk foreign key
    ];

    /**
     * Relasi ke model UserRecommendation.
     */
    public function recommendations()
    {
        return $this->hasMany(UserRecommendation::class, 'condition_id');
    }

    /**
     * Relasi ke model Treatment.
     */
    public function treatment()
    {
        return $this->belongsTo(Treatment::class, 'id_treatment');
    }

    // Model SkinCondition
    public function products()
    {
        return $this->hasMany(Product::class, 'condition_id');
    }
}
