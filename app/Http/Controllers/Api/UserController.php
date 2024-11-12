<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class UserController extends Controller
{
     // Konstruktor untuk mengaktifkan middleware
     public function __construct()
     {
         $this->middleware('auth:api'); // Middleware untuk memastikan pengguna terautentikasi
     }
 
     // Menampilkan informasi pengguna yang sedang login
     public function profile(Request $request)
     {
         // Mengambil pengguna yang sedang diautentikasi
         $user = JWTAuth::parseToken()->authenticate();
 
         // Menampilkan informasi pengguna dalam format JSON
         return response()->json([
             'user' => $user
         ]);
     }
 
     // Menampilkan hanya atribut tertentu, seperti profil
     public function getProfileInfo(Request $request)
     {
         $user = JWTAuth::parseToken()->authenticate();
 
         // Mengambil informasi user tertentu saja, misalnya username, email, profil, dll.
         $userInfo = $user->only(['username', 'email', 'profile_image', 'gender', 'age', 'level']);
 
         return response()->json([
             'user' => $userInfo
         ]);
     }
}
