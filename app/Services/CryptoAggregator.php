<?php
namespace App\Services;

use Carbon\Carbon;

class CryptoAggregator
{
    /**
     * Aggregate crypto prices data from multiple exchanges.
     *
     * @param array $exchangeData The raw prices array grouped by exchange.
     * @return array The aggregated data per crypto pair.
     */
    public static function aggregateCryptoPrices(array $exchangeData): array
    {
        $cryptoPrices = [];
        $serverTime = Carbon::now(); // Capture server time
    
        foreach ($exchangeData as $exchange => $pairs) {
            foreach ($pairs as $pairData) {
                $symbol = $pairData['symbol'];
                $averagePrice = (floatval($pairData['lowest']) + floatval($pairData['highest'])) / 2;
                $dailyChangePercentage = floatval($pairData['daily_change_percentage'] ?? 0); // Handle missing daily_change_percentage
    
                if (!isset($cryptoPrices[$symbol])) {
                    $cryptoPrices[$symbol] = [
                        'totalPrice' => 0,
                        'count' => 0,
                        'exchanges' => [],
                        'totalChangePercentage' => 0, // Initialize total change percentage
                    ];
                }
    
                $cryptoPrices[$symbol]['totalPrice'] += $averagePrice;
                $cryptoPrices[$symbol]['count']++;
                $cryptoPrices[$symbol]['exchanges'][] = $exchange;
                $cryptoPrices[$symbol]['totalChangePercentage'] += $dailyChangePercentage; // Accumulate change percentages
            }
        }
    
        $result = [];
        foreach ($cryptoPrices as $symbol => $data) {
            $averageChangePercentage = $data['count'] > 0 ? $data['totalChangePercentage'] / $data['count'] : 0; // Calculate average change
    
            $result[$symbol] = [
                'symbol' => $symbol,
                'averagePrice' => $data['count'] > 0 ? $data['totalPrice'] / $data['count'] : 0,
                'priceChange' => $averageChangePercentage, // Add price change
                'exchanges' => $data['exchanges'],
                'exchangeCount' => count($data['exchanges']),
                'serverTime' => $serverTime, // Add server time
            ];
        }
    
        return $result;
    }
}