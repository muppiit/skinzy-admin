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
        'product_id',
        'id_treatment',
    ];

    public function skinCondition()
    {
        return $this->belongsTo(SkinCondition::class, 'condition_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class, 'id_treatment');
    }

    public function userHistory()
    {
        return $this->hasMany(UserHistory::class);
    }
}
