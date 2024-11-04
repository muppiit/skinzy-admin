<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name',
        'description',
        'product_image',
        'price',
        'stok',
    ];

    public function recommendations()
    {
        return $this->hasMany(UserRecommendation::class);
    }
}
