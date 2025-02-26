<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use App\Models\CryptoPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Tests the CryptoPriceController functionality.
 */
class CryptoPriceControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the API returns the correct crypto prices.
     *
     * Given: The database contains a crypto price record.
     * When: The /api/crypto-prices endpoint is accessed.
     * Then: It should return a JSON response containing the correct data.
     */
    public function testIndexReturnsCryptoPrices()
    {
        // Arrange: Create a dummy CryptoPrice record in the database.
        CryptoPrice::factory()->create([
            'pair' => 'BTCUSDC',
            'average_price' => 10550,
        ]);

        // Ensure that the cache is flushed so that Cache::remember will call the closure.
        Cache::flush();

        // Act: Call the API endpoint.
        $response = $this->getJson('/api/crypto-prices');

        // Assert: The response should have status 200 and contain the expected structure.
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }
}
