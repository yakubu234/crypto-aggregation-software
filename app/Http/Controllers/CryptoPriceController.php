<?php

namespace App\Http\Controllers;

use App\Models\CryptoPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CryptoPriceController extends Controller
{
    
    public function index(): JsonResponse
    {
        $interval = config('services.crypto_fetch_interval') ?? 60; // Default: 60 seconds; 

        $cryptoPrices = Cache::remember('crypto_prices', $interval, function () {
            return CryptoPrice::all();
        });
    
        return response()->json([
            'status' => 'success',
            'data'   => $cryptoPrices,
        ]);
    }
}
