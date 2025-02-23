<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use App\Models\CryptoPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CryptoPriceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexReturnsCryptoPrices()
    {
        // Create a dummy CryptoPrice record.
        CryptoPrice::factory()->create([
            'pair' => 'BTCUSDC',
            'average_price' => 10550,
        ]);

        // Ensure that the cache is flushed so that Cache::remember will call the closure.
        Cache::flush();

        $response = $this->getJson('/api/crypto-prices');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }
}
