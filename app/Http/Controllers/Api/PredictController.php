<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\UserHistory;
use App\Models\SkinCondition;
use App\Models\Product;
use App\Models\Treatment;
use App\Models\UserRecommendation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Auth;

class PredictController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            DB::beginTransaction();

            $image = $request->file('image');
            $uploadedFileUrl = Cloudinary::upload($image->getRealPath(), ['folder' => 'scan-images'])->getSecurePath();

            $response = Http::post('http://127.0.0.1:8000/predict', [
                'image_url' => $uploadedFileUrl
            ]);

            if ($response->failed()) {
                throw new \Exception('FastAPI Prediction Failed: ' . $response->body());
            }

            $prediction = $response->json();
            if (!isset($prediction['acne_count'], $prediction['avg_confidence'], $prediction['condition'], $prediction['predicted_url'], $prediction['boxes'])) {
                throw new \Exception('Invalid prediction response format');
            }

            $skinCondition = SkinCondition::where('condition_name', $prediction['condition'])->firstOrFail();
            $recommendedProducts = Product::where('condition_id', $skinCondition->condition_id)->get();
            
            $productsArray = $recommendedProducts->map(fn($product) => [
                'product_name' => $product->product_name,
                'product_image' => $product->product_image,
                'description' => $product->description,
                'price' => $product->price,
                'rating' => $product->rating,
            ]);

            $userRecommendation = UserRecommendation::create([
                'condition_id' => $skinCondition->condition_id,
            ]);

            $userHistory = UserHistory::create([
                'user_id' => $user->id,
                'gambar_scan' => $uploadedFileUrl,
                'gambar_scan_predicted' => $prediction['predicted_url'],
                'detection_date' => now(),
                'recommendation_id' => $userRecommendation->recommendation_id,
                'bounding_boxes' => json_encode($prediction['boxes'])
            ]);

            $treatment = Treatment::find($skinCondition->id_treatment);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'history' => [
                        'history_id' => $userHistory->history_id,
                        'gambar_scan' => $userHistory->gambar_scan,
                        'gambar_scan_predicted' => $userHistory->gambar_scan_predicted,
                        'detection_date' => $userHistory->detection_date,
                        'recommendation_id' => $userHistory->recommendation_id,
                        // 'bounding_boxes' => json_decode($userHistory->bounding_boxes) # gak dipake
                    ],
                    'condition' => [
                        'condition_name' => $skinCondition->condition_name,
                        'description' => $skinCondition->description,
                    ],
                    'products' => $productsArray,
                    'treatment' => [
                        'deskripsi_treatment' => $treatment->deskripsi_treatment,
                    ],
                    'prediction' => $prediction
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Skin Analysis Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}