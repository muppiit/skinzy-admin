<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

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
        $userInfo = $user; // If $user is already an array containing all the attributes

        return response()->json([
            'user' => $userInfo
        ]);
    }

    // Update user information
    public function update(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $validator = Validator::make($request->all(), [
            'username' => 'string|max:255',
            'email' => 'email|max:255',
            'profile_image' => 'string|max:255',
            'gender' => 'in:l,p',
            'age' => 'integer|min:1',
            'level' => 'string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'password' => 'nullable|string|min:8|confirmed'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        // Assign new values explicitly
        $user->username = $request->username ?? $user->username;
        $user->email = $request->email ?? $user->email;
        $user->profile_image = $request->profile_image ?? $user->profile_image;
        $user->gender = $request->gender ?? $user->gender;
        $user->age = $request->age ?? $user->age;
        $user->level = $request->level ?? $user->level;
        $user->phone_number = $request->phone_number ?? $user->phone_number;
        $user->first_name = $request->first_name ?? $user->first_name;
        $user->last_name = $request->last_name ?? $user->last_name;
        $user->birth_date = $request->birth_date ?? $user->birth_date;
    
        // Hash the password if it's provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
    
        // Save and confirm
        $user->save();
    
        return response()->json([
            'message' => 'User profile updated successfully',
            'user' => $user
        ]);
    }
}
