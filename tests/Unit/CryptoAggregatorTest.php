<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CryptoAggregator;
use Carbon\Carbon;

class CryptoAggregatorTest extends TestCase
{
    public function testAggregateCryptoPricesCalculatesCorrectly()
    {
        $exchangeData = [
            'binance' => [
                [
                    'symbol' => 'BTCUSDC',
                    'lowest' => '10000',
                    'highest' => '11000',
                    'daily_change_percentage' => '2'
                ],
                [
                    'symbol' => 'BTCUSDT',
                    'lowest' => '20000',
                    'highest' => '21000',
                    'daily_change_percentage' => '3'
                ],
            ],
            'mexc' => [
                [
                    'symbol' => 'BTCUSDC',
                    'lowest' => '10100',
                    'highest' => '11100',
                    'daily_change_percentage' => '1.5'
                ],
            ],
        ];

        $result = CryptoAggregator::aggregateCryptoPrices($exchangeData);

        // Expected for BTCUSDC:
        // Average for binance: (10000+11000)/2 = 10500, for mexc: (10100+11100)/2 = 10600
        // Overall average: (10500 + 10600)/2 = 10550
        // Average change: (2 + 1.5) / 2 = 1.75

        $this->assertArrayHasKey('BTCUSDC', $result);
        $this->assertEquals(10550, $result['BTCUSDC']['averagePrice']);
        $this->assertEquals(1.75, $result['BTCUSDC']['priceChange']);
        $this->assertEquals(['binance', 'mexc'], $result['BTCUSDC']['exchanges']);

        // For BTCUSDT from binance only:
        $this->assertArrayHasKey('BTCUSDT', $result);
        $this->assertEquals(20500, $result['BTCUSDT']['averagePrice']);
        $this->assertEquals(3, $result['BTCUSDT']['priceChange']);

        // Also check that serverTime is set (it should be an instance of Carbon)
        $this->assertInstanceOf(Carbon::class, $result['BTCUSDC']['serverTime']);
    }
}
