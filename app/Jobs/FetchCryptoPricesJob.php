<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\CryptoAggregator;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Http;

class FetchCryptoPricesJob implements ShouldQueue
{
    use Queueable;
    protected array $pairs;
    protected array $exchanges;
    protected string $url;
    protected string $apiKey;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->pairs = config('exchange.pairs.list');
        $this->exchanges = config('exchange.exchange.list');
        $this->url = config('exchange.url');
        $this->apiKey = config('exchange.api_key');
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Build the symbol string (e.g., "BTC+ETH+ETHBTC")
        $symbolParam = implode('+', $this->pairs);

        try {
            // Fetch prices from all exchanges in parallel using pooling with a timeout.
            $responses = Http::pool(function ($pool) use ($symbolParam) {
                return array_map(
                    function ($exchange) use ($pool, $symbolParam) {
                        return $pool->withHeaders([
                            'Authorization' => 'Bearer '.$this->apiKey,
                        ])
                        ->timeout(5) // set a timeout in seconds
                        ->get("{$this->url}?symbol={$symbolParam}%40{$exchange}");
                    },
                    $this->exchanges
                );
            });
        } catch (Exception $e) {
            // Log the exception and optionally rethrow it to trigger a job retry.
            Log::error('Error during crypto price fetching: ' . $e->getMessage());
            throw $e;
        }

        $exchangeData = [];
        // Process each response
        foreach ($responses as $index => $result) {
            $exchangeName = $this->exchanges[$index];

            if ($result->failed()) {
                // Log detailed error information.
                Log::error("Error fetching data for exchange {$exchangeName}: " . $result->body());
                // Optionally, you can decide to retry the job or handle the failure gracefully.
                continue;
            }

            $data = $result->json();
            // Check if symbols key exists before processing.
            $symbols = isset($data['symbols']) ? $data['symbols'] : [];

            $exchangeData[$exchangeName] = $symbols;
        }

        // If no data was successfully fetched, consider throwing an exception or handling accordingly.
        if (empty($exchangeData)) {
            Log::warning('No crypto data was fetched from any exchange.');
            return;
        }

        // Aggregate crypto prices.
        $result = CryptoAggregator::aggregateCryptoPrices($exchangeData);

        // Dispatch the event with raw data (isProcessed = false by default).
        event(new \App\Events\CryptoPriceUpdated($result));

        Log::info('Cryptocurrency prices fetch job completed.');
    }
}
