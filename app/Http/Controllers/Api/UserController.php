<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function profile(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json([
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving user profile: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to retrieve user profile.'], 500);
        }
    }

    public function getProfileInfo(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
    
            // Get the profile image URL if it exists
            $profileImageUrl = null;
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                // Generate the full URL to the profile image stored in 'public' disk
                $profileImageUrl = Storage::disk('public')->url($user->profile_image);
            }
    
            // Return the user profile along with the profile image URL
            return response()->json([
                'user' => $user,
                'profile_image' => $profileImageUrl
            ]);
        } catch (\Exception $e) {
            // Log the error and return an appropriate message
            Log::error('Error retrieving user profile info: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to retrieve user profile info.'], 500);
        }
    }
    
    public function update(Request $request)
    {
        try {
            // Log incoming data for debugging
            Log::info('Request data:', $request->all());
    
            $user = JWTAuth::parseToken()->authenticate();
    
            // Validation rules
            $rules = [
                'username' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'gender' => 'nullable|in:l,p',
                'age' => 'nullable|integer|min:1',
                'level' => 'nullable|string|max:255',
                'phone_number' => 'nullable|string|max:20',
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'birth_date' => 'nullable|date',
                'password' => 'nullable|string|min:8|confirmed'
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                Log::error('Validation failed: ' . json_encode($validator->errors()));
                return response()->json(['error' => $validator->errors()], 422);
            }
    
            // Update other user fields if provided
            $fillableFields = ['username', 'email', 'gender', 'age', 'level', 'phone_number', 'first_name', 'last_name', 'birth_date'];
            foreach ($fillableFields as $field) {
                if ($request->has($field)) {
                    $user->$field = $request->input($field);
                }
            }
    
            // Handle password update if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }
    
            // Save the updated user
            $user->save();
    
            return response()->json([
                'message' => 'User profile updated successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating user profile: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while updating the profile.'], 500);
        }
    }

    public function updateProfileImage(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
    
            // Validate the profile image upload
            $request->validate([
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            // File upload handling
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
    
                if ($file->isValid()) {
                    // Remove old profile image if it exists
                    if ($user->profile_image) {
                        Storage::disk('public')->delete($user->profile_image);
                    }
    
                    // Store the new image
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('profile-images', $fileName, 'public');
    
                    $user->profile_image = $filePath;
                    $user->save();
    
                    return response()->json([
                        'message' => 'Profile image updated successfully',
                        'profile_image' => $filePath
                    ]);
                } else {
                    return response()->json(['error' => 'Invalid file upload'], 422);
                }
            }
    
            return response()->json(['error' => 'No file uploaded'], 422);
        } catch (\Exception $e) {
            Log::error('Error updating profile image: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while updating the profile image.'], 500);
        }
    }
    
}
