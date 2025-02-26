<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CryptoAggregatorService;
use Carbon\Carbon;

/**
 * Tests the CryptoAggregator class, which calculates
 * the average prices and price changes of cryptocurrencies.
 */
class CryptoAggregatorTest extends TestCase
{
    /**
     * Test that the CryptoAggregator calculates the correct averages.
     *
     * Given: Crypto prices from multiple exchanges.
     * When: The aggregateCryptoPrices method is called.
     * Then: It should return the correct average price and price change percentage.
     */
    public function testAggregateCryptoPricesCalculatesCorrectly()
    {
        // Arrange: Define sample exchange data.
        $exchangeData = [
            'binance' => [
                [
                    'symbol' => 'BTCUSDC',
                    'lowest' => '10000',
                    'highest' => '11000',
                    'last' => '10500',
                    'daily_change_percentage' => '2'
                ],
                [
                    'symbol' => 'BTCUSDT',
                    'lowest' => '20000',
                    'last' => '22000',
                    'highest' => '21000',
                    'daily_change_percentage' => '3'
                ],
            ],
            'mexc' => [
                [
                    'symbol' => 'BTCUSDC',
                    'lowest' => '10100',
                    'last' => '11050',
                    'highest' => '11100',
                    'daily_change_percentage' => '1.5'
                ],
            ],
        ];

        // Act: Run the aggregator logic.
        $result = CryptoAggregatorService::aggregateCryptoPrices($exchangeData);

        // Assert: Verify calculations for BTCUSDC.
        // Expected:
        // Binance: 10500 (last)
        // Mexc : 11050 (last)
        // Overall avg: (10500 + 11050) / 2 = 10,775
        // Avg daily change: (2 + 1.5) / 2 = 1.75

        $this->assertArrayHasKey('BTCUSDC', $result);
        $this->assertEquals(10775, $result['BTCUSDC']['averagePrice']);
        $this->assertEquals(1.75, $result['BTCUSDC']['priceChange']);
        $this->assertEquals(['binance', 'mexc'], $result['BTCUSDC']['exchanges']);

        // Assert: Verify calculations for BTCUSDT.
        // Expected: (10500 + 11050) / 2 = 10,775, Change: 1.75%
        $this->assertArrayHasKey('BTCUSDT', $result);
        $this->assertEquals(22000, $result['BTCUSDT']['averagePrice']);
        $this->assertEquals(3, $result['BTCUSDT']['priceChange']);

        // Assert: Ensure serverTime is set correctly.
        $this->assertInstanceOf(Carbon::class, $result['BTCUSDC']['serverTime']);
    }
}
