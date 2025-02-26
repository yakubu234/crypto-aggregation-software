<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\CryptoAggregatorService;
use App\Services\CryptoPriceFetcherService;
use Illuminate\Support\Facades\Log;
use Exception;

class FetchCryptoPricesJob implements ShouldQueue
{
    use Queueable;

    /**
     * The maximum number of times the job may be attempted.
     */
    public int $tries = 5; // Retry up to 5 times

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 10; // Wait 10 seconds before retrying


    public function handle()
    {
        try {
            $fetcherService = new CryptoPriceFetcherService();
            // Fetch crypto prices from API
            $exchangeData = $fetcherService->fetchPrices();

            if (empty($exchangeData)) {
                Log::warning('No crypto data fetched from any exchange.');
                return;
            }

            // Aggregate fetched data
            $aggregatorService = new CryptoAggregatorService();
            $result = $aggregatorService->aggregateCryptoPrices($exchangeData);

            // Dispatch event
            event(new \App\Events\CryptoPriceUpdated($result));

            Log::info('Cryptocurrency prices fetch job completed.');
        } catch (Exception $e) {
            Log::error('Error in FetchCryptoPricesJob: ' . $e->getMessage());
            throw $e;
        }
    }
}