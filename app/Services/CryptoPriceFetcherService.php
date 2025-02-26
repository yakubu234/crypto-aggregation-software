<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class CryptoPriceFetcherService
{
    protected string $url;
    protected string $apiKey;
    protected array $pairs;
    protected array $exchanges;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->url = config('exchange.url');
        $this->apiKey = config('exchange.api_key');
        $this->exchanges = config('exchange.exchange.list');
        $this->pairs = config('exchange.pairs.list');
    }

    /**
     * Fetch prices from all exchanges in parallel using pooling with a timeout.
     */
    public function fetchPrices(): array
    {
        $pairs = implode('+', $this->pairs);
        $exchangeData = [];

        try {
            // Fetch data from multiple exchanges using HTTP pooling.
            $responses = Http::pool(function ($pool) use ($pairs) {
                return array_map(
                    function ($exchange) use ($pool, $pairs) {
                        return $pool->withHeaders([
                            'Authorization' => 'Bearer ' . $this->apiKey,
                        ])
                        ->timeout(5) // Set timeout for each request
                        ->get("{$this->url}?symbol={$pairs}%40{$exchange}");
                    },
                    $this->exchanges
                );
            });
        } catch (Exception $e) {
            // Log the exception and optionally rethrow it to trigger a job retry.
            Log::error('Error fetching crypto prices: ' . $e->getMessage());
            return [];
        }

        // Process each response
        foreach ($responses as $index => $result) {
            $exchangeName = $this->exchanges[$index];

            if ($result->failed()) {
                Log::error("Error fetching data from {$exchangeName}: " . $result->body());
                continue;
            }

            $data = $result->json();
            $exchangeData[$exchangeName] = $data['symbols'] ?? [];
        }

        return $exchangeData;
    }
}
