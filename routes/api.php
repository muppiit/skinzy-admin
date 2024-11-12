<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->middleware('jwt.verify');
Route::post('refresh', [LoginController::class, 'refresh'])->middleware('jwt.verify');
Route::post('register', [RegisterController::class, 'register']);
Route::middleware('auth:api')->get('/user/profile', [UserController::class, 'profile']);
Route::middleware('auth:api')->get('/user/profile-info', [UserController::class, 'getProfileInfo']);

// Rute lain yang memerlukan autentikasi JWT
Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', function() {
        return auth()->user();
    });
});