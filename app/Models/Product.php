<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_name',
        'description',
        'product_image',
        'rating',
        'price',
        'stok',
        'condition_id', // Tambahkan condition_id ke atribut yang bisa diisi
    ];

    /**
     * Relasi ke tabel UserRecommendation
     */
    public function recommendations()
    {
        return $this->hasMany(UserRecommendation::class);
    }

    /**
     * Relasi ke tabel SkinCondition
     */
    public function skinCondition()
    {
        return $this->belongsTo(SkinCondition::class, 'condition_id', 'condition_id');
    }

}
