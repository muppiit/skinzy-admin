<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\UserHistory;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); // Middleware otentikasi
    }

    // Fungsi untuk mendapatkan pengguna yang sedang login
    private function getAuthenticatedUser()
    {
        return JWTAuth::parseToken()->authenticate();
    }

    // Menampilkan informasi pengguna yang sedang login
    public function profile()
    {
        return response()->json(['user' => $this->getAuthenticatedUser()]);
    }

    // Menampilkan hanya atribut tertentu dari profil
    public function getProfileInfo()
    {
        $user = $this->getAuthenticatedUser();
        return response()->json(['user' => $user]);
    }

    // Fungsi untuk validasi input pengguna
    private function validateUserInput($request, $isProfileImageRequired = false)
    {
        $rules = [
            'username' => 'string|max:255',
            'email' => 'email|max:255',
            'profile_image' => $isProfileImageRequired ? 'required|image|mimes:jpeg,png,jpg,gif|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'in:l,p',
            'age' => 'integer|min:1',
            'level' => 'string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'password' => 'nullable|string|min:8|confirmed'
        ];
        
        return Validator::make($request->all(), $rules);
    }

    // Update profil pengguna
    public function update(Request $request)
    {
        $user = $this->getAuthenticatedUser();
    
        // Validasi input
        $validator = $this->validateUserInput($request);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        // Update profil
        $user->username = $request->username ?? $user->username;
        $user->email = $request->email ?? $user->email;
        $user->gender = $request->gender ?? $user->gender;
        $user->age = $request->age ?? $user->age;
        $user->level = $request->level ?? $user->level;
        $user->phone_number = $request->phone_number ?? $user->phone_number;
        $user->first_name = $request->first_name ?? $user->first_name;
        $user->last_name = $request->last_name ?? $user->last_name;
        $user->birth_date = $request->birth_date ?? $user->birth_date;
    
        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
    
        // Simpan perubahan
        $user->save();
    
        return response()->json([
            'message' => 'User profile updated successfully',
            'user' => $user
        ]);
    }
    
    // Update gambar profil pengguna
    public function updateProfileImage(Request $request)
    {
        try {
            $user = $this->getAuthenticatedUser();

            // Validasi gambar profil
            $validator = $this->validateUserInput($request, true);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                if ($user->profile_image) {
                    Cloudinary::destroy($user->profile_image); // Menghapus gambar lama
                }
                // Upload gambar ke Cloudinary
                $result = Cloudinary::upload($file->getRealPath(), ['folder' => 'profile-images']);
                $user->profile_image = $result->getSecurePath();
                $user->save();

                return response()->json([
                    'message' => 'Profile image updated successfully',
                    'profile_image' => $user->profile_image,
                ]);
            }

            return response()->json(['error' => 'No file uploaded'], 422);
        } catch (\Exception $e) {
            Log::error('Error updating profile image: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while updating the profile image.'], 500);
        }
    }

    public function getUserHistory()
    {
        // Get the authenticated user
        $user = $this->getAuthenticatedUser();
    
        // Fetch the user's history along with the related recommendations
        $userHistories = UserHistory::with('recommendation.skinCondition', 'recommendation.product')
            ->where('user_id', $user->id)
            ->get();
    
        // Check if there are any histories
        if ($userHistories->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No history found for this user'
            ], 200);
        }
    
        // Return the history with the related user recommendations
        return response()->json([
            'status' => 'success',
            'data' => $userHistories
        ], 200);
    }
    
}
