<?php

namespace Database\Factories;
use App\Models\CryptoPrice;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CryptoPrice>
 */
class CryptoPriceFactory extends Factory
{
    protected $model = CryptoPrice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pair'       => $this->faker->randomElement(['BTCUSDC', 'BTCUSDT', 'BTCETH']),
            'exchange'       => $this->faker->randomElement(['binance','mexc','kucoin','gate','huobi','bybit','bitget']),
            'price_change'       => $this->faker->randomElement(['-02239392.3323', '+11.23000203', '+0.22222']),
            'average_price' => $this->faker->randomFloat(2, 10000, 50000),
            'timestamp' => now()
        ];
    }
}

