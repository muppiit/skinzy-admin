<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkincareCheckout extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'skincare_checkout';

    // Primary key tabel
    protected $primaryKey = 'id_checkout';

    protected $fillable = [
        'id_history',
        'quantity',
        'total_harga',
    ];

    public function history()
    {
        return $this->belongsTo(UserHistory::class, 'id_history', 'history_id');
    }
}
