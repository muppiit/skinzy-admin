<?php

use App\Http\Controllers\Api\PredictController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\SkinConditionController;
use App\Http\Controllers\Api\SkinpediaController;
use App\Http\Controllers\Api\TreatmentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CheckoutController;

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
Route::middleware('auth:api')->put('/user/update', [UserController::class, 'update']);
Route::post('/user/update-profile-image', [UserController::class, 'updateProfileImage'])->middleware('auth:api');
Route::post('/user/update-address', action: [UserController::class, 'updateAddress'])->middleware('auth:api');

Route::post('/analyze-skin', [PredictController::class, 'analyze']);

Route::get('/user/history', [UserController::class, 'getUserHistory']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/brands', [ProductController::class, 'indexBrands']); // New route for distinct brands
Route::get('/skin-conditions', [SkinConditionController::class, 'index']);
Route::get('/treatments', [TreatmentController::class, 'index']);
Route::get('/skinpedia', [SkinpediaController::class, 'index']);
Route::middleware(['auth:api'])->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'checkout']);
    // Route::get('/getCheckout', [CheckoutController::class, 'getUserCheckouts']);
});

// Rute lain yang memerlukan autentikasi JWT
Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('user', function () {
        return auth()->user();
    });
});
