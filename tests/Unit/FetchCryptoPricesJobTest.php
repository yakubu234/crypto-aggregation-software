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



class FetchCryptoPricesJobTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Facade::setFacadeApplication($this->app);
    }

    public function testHandleFetchesAndDispatchesEvent()
    {
        // Set up configuration for pairs, exchanges, API URL, and API key.
        $pairs = ['BTCUSDC', 'BTCUSDT'];
        $exchanges = ['binance', 'mexc'];
        config()->set('exchange.pairs.list', $pairs);
        config()->set('exchange.exchange.list', $exchanges);
        config()->set('exchange.url', 'http://fakeapi.test');
        config()->set('exchange.api_key', 'testapikey');

        // Prepare fake responses for both exchanges.
        $fakeResponseBinance = [
            'symbols' => [
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
        ];
        $fakeResponseMexc = [
            'symbols' => [
                [
                    'symbol' => 'BTCUSDC',
                    'lowest' => '10100',
                    'highest' => '11100',
                    'daily_change_percentage' => '1.5'
                ],
            ],
        ];

        // Use Http::fake() to simulate parallel HTTP responses.
        Http::fake([
            // Use closure to return different responses for each exchange.
            '*' => function ($request) use ($fakeResponseBinance, $fakeResponseMexc, $exchanges) {
                if (strpos($request->url(), $exchanges[0]) !== false) {
                    return Http::response($fakeResponseBinance, 200);
                }
                return Http::response($fakeResponseMexc, 200);
            },
        ]);

        // Fake event dispatching.
        Event::fake();

        $job = new FetchCryptoPricesJob();
        $job->handle();

        Event::assertDispatched(CryptoPriceUpdated::class);
    }

}
