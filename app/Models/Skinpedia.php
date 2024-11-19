<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skinpedia extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dari nama model (opsional)
    protected $table = 'skinpedia';

    protected $primaryKey = 'id_skinpedia';
    // Tentukan kolom yang dapat diisi (mass assignment)
    protected $fillable = [
        'judul',      // Kolom untuk judul
        'deskripsi',  // Kolom untuk deskripsi
        'gambar',     // Kolom untuk gambar
    ];
}
