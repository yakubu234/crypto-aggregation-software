<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Jobs\FetchCryptoPricesJob;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Event;
use App\Events\CryptoPriceUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Facade;
use Mockery;

/**
 * Tests the FetchCryptoPricesJob functionality.
 */
class FetchCryptoPricesJobTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Facade::setFacadeApplication($this->app);
    }

    /**
     * Test that the FetchCryptoPricesJob fetches data and dispatches an event.
     *
     * Given: Configured crypto pairs and exchanges.
     * When: The job executes.
     * Then: It should fetch data from all exchanges and dispatch a CryptoPriceUpdated event.
     */
    public function testHandleFetchesAndDispatchesEvent()
    {
        // Arrange: Set up configuration for crypto pairs, exchanges, and API.
        $pairs = ['BTCUSDC', 'BTCUSDT'];
        $exchanges = ['binance', 'mexc'];
        config()->set('exchange.pairs.list', $pairs);
        config()->set('exchange.exchange.list', $exchanges);
        config()->set('exchange.url', 'http://fakeapi.test');
        config()->set('exchange.api_key', 'testapikey');

        // Arrange: Prepare fake API responses.
        $fakeResponseBinance = [
            'symbols' => [
                [
                    'symbol' => 'BTCUSDC',
                    'lowest' => '10000',
                    'highest' => '11000',
                    'last' => '11500',
                    'daily_change_percentage' => '2'
                ],
                [
                    'symbol' => 'BTCUSDT',
                    'lowest' => '20000',
                    'highest' => '21000',
                    'last' => '22000',
                    'daily_change_percentage' => '3'
                ],
            ],
        ];
        $fakeResponseMexc = [
            'symbols' => [
                [
                    'symbol' => 'BTCUSDC',
                    'lowest' => '10100',
                    'highest' => '11100',
                    'last' => '11050',
                    'daily_change_percentage' => '1.5'
                ],
            ],
        ];

        // Mock API responses.
        Http::fake([
            '*' => function ($request) use ($fakeResponseBinance, $fakeResponseMexc, $exchanges) {
                if (strpos($request->url(), $exchanges[0]) !== false) {
                    return Http::response($fakeResponseBinance, 200);
                }
                return Http::response($fakeResponseMexc, 200);
            },
        ]);

        // Fake event dispatching.
        Event::fake();

        // Act: Execute the job.
        $job = new FetchCryptoPricesJob();
        $job->handle();

        // Assert: Verify the CryptoPriceUpdated event was dispatched.
        Event::assertDispatched(CryptoPriceUpdated::class);
    }
}
