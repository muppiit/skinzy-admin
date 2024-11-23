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
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PredictController extends Controller
{
    public function analyze(Request $request)
    {
        // Validasi input image
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            // Autentikasi user dengan JWT
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not authenticated.'], 401);
            }

            DB::beginTransaction();

            // Upload gambar ke Cloudinary
            $image = $request->file('image');
            $uploadedFileUrl = Cloudinary::upload($image->getRealPath(), ['folder' => 'scan-images'])->getSecurePath();

            // Kirim URL gambar ke FastAPI untuk prediksi
            $response = Http::post('https://da2f22ff2fc11c89a42512ef2271a602.serveo.net/predict', [
                'image_url' => $uploadedFileUrl
            ]);

            // Tangani response error dari FastAPI
            if ($response->failed()) {
                Log::error('FastAPI Error Response: ' . $response->body());
                throw new \Exception('FastAPI Prediction Failed: ' . $response->body());
            }

            // Ambil hasil prediksi dari FastAPI
            $prediction = $response->json();
            if (is_array($prediction) && isset($prediction['class'], $prediction['confidence'])) {
                $predictedClass = $prediction['class'];
                $confidence = $prediction['confidence'];
            } else {
                throw new \Exception('Invalid prediction response format');
            }

            // Mapping prediksi ke kondisi kulit
            $skinCondition = $this->getSkinCondition($predictedClass);

            // Memanggil semua produk dengan condition_id yang sama
            $recommendedProducts = Product::where('condition_id', '=', $predictedClass + 1)->get();

            // Map produk ke dalam format yang diinginkan untuk respons
            $productsArray = $recommendedProducts->map(function ($product) {
                return [
                    'product_name' => $product->product_name,
                    'product_image' => $product->product_image,
                    'description' => $product->description,
                    'price' => $product->price,
                    'rating' => $product->rating,
                ];
            });

            // Simpan data rekomendasi
            $userRecommendation = UserRecommendation::create([
                'condition_id' => $skinCondition->condition_id,
            ]);

            // Simpan riwayat pengguna
            $userHistory = UserHistory::create([
                'user_id' => $user->id,
                'gambar_scan' => $uploadedFileUrl,
                'detection_date' => now(),
                'recommendation_id' => $userRecommendation->recommendation_id,
            ]);

            // Ambil informasi treatment dari kondisi kulit
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
                    'products' => $productsArray,  // Mengirim produk yang sudah dipetakan
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
        return SkinCondition::where('condition_id', $predictedClass + 1)->firstOrFail();
    }
}
