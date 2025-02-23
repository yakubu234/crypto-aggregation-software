<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Events\CryptoPriceUpdated;

class CryptoPriceUpdatedEventTest extends TestCase
{
    public function testBroadcastWithWhenProcessed()
    {
        $data = ['symbol' => 'BTCUSDC', 'averagePrice' => 10550];
        $event = new CryptoPriceUpdated($data, true);

        $this->assertEquals(['crypto' => $data], $event->broadcastWith());
    }

    public function testBroadcastWithWhenNotProcessed()
    {
        $data = ['symbol' => 'BTCUSDC', 'averagePrice' => 10550];
        $event = new CryptoPriceUpdated($data, false);

        // When not processed, the event should not broadcast any payload.
        $this->assertEmpty($event->broadcastWith());
    }
}
