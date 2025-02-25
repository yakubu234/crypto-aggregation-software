<?php

return [


    /*
    |--------------------------------------------------------------------------
    | List or pairs
    |--------------------------------------------------------------------------
    |
    | This list types of pairs available. 
    
    |
    */

    'pairs' => [
        'list' =>  explode(',', env('CRYPTO_PAIRS', 'BTCUSDT,BTCUSDC,BTCUSD,BTCEUR,BTCETH,BTCBNB,BTCADA,BTCSOL,ETHUSDT,ETHUSDC,ETHUSD,ETHBTC,ETHBNB,ETHSOL,ETHMATIC,USDTUSDC,USDTDAI,USDTBUSD,BNBUSDT,SOLUSDT,XRPUSDT,ADAUSDT,MATICUSDT,DOGEUSDT,LTCUSDT,BTCUSD,ETHEUR,BNBGBP,ADAJPY'))
    ],

    /*
    |--------------------------------------------------------------------------
    | List or exchange
    |--------------------------------------------------------------------------
    |
    | This list types of exchange available. 
    
    |
    */

    'exchange' => [
        'list' =>  explode(',', env('CRYPTO_EXCHANGES', 'binance,mexc,kucoin,huobi,bybit'))
    ],

    'url' =>  env('CRYPTO_API_URL', 'https://api.freecryptoapi.com/v1/getData'),
    'api_key' => env('CRYPTO_API_KEY'),
];
