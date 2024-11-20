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
use Tymon\JWTAuth\Facades\JWTAuth;

class PredictController extends Controller
{
    public function analyze(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not authenticated.'], 401);
            }

            DB::beginTransaction();

            // Upload image
            $image = $request->file('image');
            $imagePath = $image->store('scan_images', 'public');

            // Send to FastAPI for prediction
            $response = Http::attach(
                'file',
                file_get_contents($image->getRealPath()),
                $image->getClientOriginalName()
            )->post('http://127.0.0.1:8000/predict');

            Log::info('Response from FastAPI POST: ' . $response->body());

            if ($response->failed()) {
                throw new \Exception('FastAPI Prediction Failed: ' . $response->body());
            }

            $prediction = $response->json();

            Log::info('Prediction Response: ', ['prediction' => $prediction]);

            if (is_array($prediction) && isset($prediction['class'], $prediction['confidence'])) {
                $predictedClass = $prediction['class'];
                $confidence = $prediction['confidence'];
            } else {
                throw new \Exception('Invalid prediction response format');
            }

            // Map prediction to skin condition
            $skinCondition = $this->getSkinCondition($predictedClass);

            // Save recommended product to database
            $recommendedProduct = Product::where('rating', '>=', 4)
                ->inRandomOrder()
                ->firstOrFail();

            $userRecommendation = UserRecommendation::create([
                'condition_id' => $skinCondition->condition_id,
                // 'product_id' => $recommendedProduct->product_id,
            ]);

            // Save user history
            $userHistory = UserHistory::create([
                'user_id' => $user->id,
                'gambar_scan' => $imagePath,
                'detection_date' => now(),
                'recommendation_id' => $userRecommendation->recommendation_id,
            ]);

            $treatment = Treatment::find($skinCondition->id_treatment);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'history' => [
                        'history_id' => $userHistory->history_id,
                        'gambar_scan' => $userHistory->gambar_scan,
                        'detection_date' => $userHistory->detection_date,
                        'recommendation_id' => $userHistory->recommendation_id,
                    ],
                    'condition' => [
                        'condition_name' => $skinCondition->condition_name,
                        'description' => $skinCondition->description,
                    ],
                    'product' => [
                        'product_name' => $recommendedProduct->product_name,
                        'description' => $recommendedProduct->description,
                        'price' => $recommendedProduct->price,
                        'rating' => $recommendedProduct->rating,
                    ],
                    'treatment' => [
                        'deskripsi_treatment' => $treatment->deskripsi_treatment,
                    ],
                    'prediction' => [
                        'class' => $predictedClass,
                        'confidence' => $confidence,
                    ],
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Skin Analysis Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function getSkinCondition($predictedClass)
    {
        $conditionMapping = [
            0 => 'Rendah',
            1 => 'Sedang',
            2 => 'Parah',
            3 => 'Sangat Parah',
        ];

        $conditionName = $conditionMapping[$predictedClass] ?? 'Sedang';

        Log::info('Skin Condition: ' . $conditionName);

        return SkinCondition::where('condition_id', $predictedClass+1)->firstOrFail();
    }
}
