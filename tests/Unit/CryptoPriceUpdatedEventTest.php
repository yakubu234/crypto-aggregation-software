<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Events\CryptoPriceUpdated;

/**
 * Unit tests for the CryptoPriceUpdated event.
 *
 * This event is triggered when cryptocurrency prices are updated.
 * It broadcasts real-time updates **only if the data is marked as processed**.
 */
class CryptoPriceUpdatedEventTest extends TestCase
{
    /**
     * Test that the event correctly returns broadcast data when processed.
     *
     * Given: A `CryptoPriceUpdated` event with processed data.
     * When: The `broadcastWith()` method is called.
     * Then: It should return the crypto data inside a `crypto` key.
     */
    public function testBroadcastWithWhenProcessed()
    {
        // Arrange: Define sample cryptocurrency data.
        $data = ['symbol' => 'BTCUSDC', 'averagePrice' => 10550];

        // Act: Create an event instance with processed = true.
        $event = new CryptoPriceUpdated($data, true);

        // Assert: The event should return the crypto data in the expected structure.
        $this->assertEquals(['crypto' => $data], $event->broadcastWith());
    }

    /**
     * Test that the event does not broadcast any data when not processed.
     *
     * Given: A `CryptoPriceUpdated` event with unprocessed data.
     * When: The `broadcastWith()` method is called.
     * Then: It should return an empty array, meaning no data is broadcasted.
     */
    public function testBroadcastWithWhenNotProcessed()
    {
        // Arrange: Define sample cryptocurrency data.
        $data = ['symbol' => 'BTCUSDC', 'averagePrice' => 10550];

        // Act: Create an event instance with processed = false.
        $event = new CryptoPriceUpdated($data, false);

        // Assert: The event should return an empty array, meaning nothing is broadcasted.
        $this->assertEmpty($event->broadcastWith());
    }
}
