<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Gunakan Authenticatable
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject; // Tambahkan ini untuk JWT

class User extends Authenticatable implements JWTSubject // Ganti dari 'Users' ke 'User'
{
    use HasFactory, Notifiable; // Tambahkan Notifiable untuk notifikasi, jika diperlukan

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'profile_image',
        'gender',
        'age',
        'level',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', // Sembunyikan password saat data diambil
        'remember_token',
    ];

    /**
     * Relasi dengan model UserHistory
     */
    public function userHistories()
    {
        return $this->hasMany(UserHistory::class);
    }

    /**
     * Relasi dengan model UserRecommendation
     */
    public function recommendations()
    {
        return $this->hasMany(UserRecommendation::class);
    }

    /**
     * Mengambil identifier yang akan disimpan di dalam token JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Mengambil array custom claims yang akan ditambahkan ke token JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}