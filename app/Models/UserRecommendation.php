<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRecommendation extends Model
{
    use HasFactory;

    protected $primaryKey = 'recommendation_id';

    protected $fillable = [
        'condition_id',
        // 'product_id',
    ];

    /**
     * Relasi ke model SkinCondition.
     */
    public function skinCondition()
    {
        return $this->belongsTo(SkinCondition::class, 'condition_id');
    }
    public function condition()
    {
        return $this->belongsTo(SkinCondition::class, 'condition_id');
    }
    /**
     * Relasi ke model UserHistory.
     */
    public function userHistory()
    {
        return $this->hasMany(UserHistory::class);
    }
}
