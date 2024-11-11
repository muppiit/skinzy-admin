<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHistory extends Model
{
    use HasFactory;
    protected $primaryKey = 'history_id';

    protected $fillable = [
        'user_id',
        'gambar_scan',
        'detection_date',
        'recommendation_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recommendation()
    {
        return $this->belongsTo(UserRecommendation::class, 'recommendation_id');
    }
}
