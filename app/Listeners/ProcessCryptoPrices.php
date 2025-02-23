<?php

namespace App\Listeners;

use App\Events\CryptoPriceUpdated;
use App\Models\CryptoPrice;
use App\Services\CurrencyFormartter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;

class ProcessCryptoPrices implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  CryptoPricesFetched  $event
     * @return void
     */
    public function handle(CryptoPriceUpdated $event): void
    {

        // Only process if the event has raw data.
        if (!$event->isProcessed) {
            // Assume $event->data is an array of price records.
            $updatedPairs = new Collection(); 
            foreach ($event->cryptos as $priceData) {
                // Process and persist each record.
                // For example, you might compute an average here if needed.
                $cryptoPrice = CryptoPrice::updateOrCreate(
                    ['pair' => $priceData['symbol']], // Unique identifier for the currency pair
                    [
                        'exchange' => implode(",", $priceData['exchanges']),
                        'average_price' => $priceData['averagePrice'],
                        'price_change' => $priceData['priceChange'],
                        'timestamp' => $priceData['serverTime'],
                    ]
                );
        
                $updatedPairs->push($priceData['symbol']); // Add the pair to the collection
        
            }
        
            $updatedCryptoPrices = CryptoPrice::whereIn('pair', $updatedPairs)->get();

            // Re-dispatch the event with the processed (persisted) data,
            // so it will be broadcast to the frontend.
            event(new CryptoPriceUpdated($updatedCryptoPrices, true));
        }
    }
}
