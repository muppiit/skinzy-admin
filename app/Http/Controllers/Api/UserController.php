<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
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
            'gender' => 'nullable|in:l,p',
            'age' => 'nullable|integer|min:1',
            'level' => 'nullable|string|max:255',
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
    // Get the authenticated user
    $user = $this->getAuthenticatedUser();

    // Validate input data
    $validator = $this->validateUserInput($request);
    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    // Validate password if provided
    if ($request->filled('password') && strlen($request->password) < 8) {
        return response()->json(['error' => 'Password must be at least 8 characters long.'], 422);
    }

    // Update profile fields
    $user->username = $request->username ?? $user->username;
    $user->email = $request->email ?? $user->email;

    // Ensure email is unique if it's being updated
    if ($request->filled('email') && $request->email !== $user->email) {
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return response()->json(['error' => 'Email is already taken.'], 422);
        }
    }

    $user->gender = $request->gender ?? $user->gender;
    $user->age = $request->age ?? $user->age;
    $user->level = $request->level ?? $user->level;
    $user->phone_number = $request->phone_number ?? $user->phone_number;
    $user->first_name = $request->first_name ?? $user->first_name;
    $user->last_name = $request->last_name ?? $user->last_name;

    // Update birth_date if provided and valid
    if ($request->filled('birth_date')) {
        try {
            $user->birth_date = Carbon::createFromFormat('Y-m-d', $request->birth_date);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid birth date format. Please use YYYY-MM-DD.'], 422);
        }
    }

    // Update password if provided
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    // Save the updated user data
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
        $userHistories = UserHistory::with([
            'recommendation.skinCondition',
            'recommendation.condition.products',  // Add this line to eager load products
            'recommendation.condition.treatment'  // Add this line to eager load products
        ])
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
