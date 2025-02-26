<?php

namespace App\Services;

use App\Models\CryptoPrice;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;

class CryptoPriceService
{
    public function getCryptoPrices(): JsonResponse
    {
        $interval = config('services.crypto_fetch_interval') ?? 60; // Default: 60 seconds

        $cryptoPrices =  Cache::remember('crypto_prices', $interval, function () {
            return CryptoPrice::all();
        });

        return response()->json([
            'status' => 'success',
            'data'   => $cryptoPrices,
        ]);
    }
}
