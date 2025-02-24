<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Crypto Configuration
    |--------------------------------------------------------------------------
    |
    | This option controls the default crypto configuration that will be used by the
    | framework. This connection is utilized if another isn't explicitly
    | specified when running a crypto operation inside the application.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Default Crypto Pairs
    |--------------------------------------------------------------------------
    |
    | This option controls the default crypto pair that will be used by the
    | application. This is used when fetching exchange rates from the set api.
    |
    */

    'pairs' => explode(',', env('CRYPTO_PAIRS', 'BTCUSDC,BTCUSDT,BTCETH')),

    /*
    |--------------------------------------------------------------------------
    | Default Crypto Exchanges
    |--------------------------------------------------------------------------
    |
    | This option controls the default crypto exchanges that will be used by the
    | application. This is used when fetching exchange rates from the set api.
    |
    */

    'exchanges' => explode(',', env('CRYPTO_EXCHANGES', 'binance,mex,huobi')),

    /*
    |--------------------------------------------------------------------------
    | Crypto Fetch Interval
    |--------------------------------------------------------------------------
    |
    | This option controls the default crypto fetch interval that will be used by the
    | application. This is used when fetching exchange rates from the set api.
    |
    */

    'interval' => env('CRYPTO_API_FETCH_INTERVAL', 5),

    /*
    |--------------------------------------------------------------------------
    | Crypto API Configuration
    |--------------------------------------------------------------------------
    |
    | This option controls the default crypto api service that will be used by the
    | application to fetch exchange rates. Each api service needs to be configured separately.
    | Currently supported api services: "freecryptoapi"
    |
    */
    'apis' => [
        'default' => env('CRYPTO_DEFAULT_API', 'freecryptoapi'),
        'freecryptoapi' => [
            'api_url' => env('FREE_CRYPTO_API_URL', 'https://api.freecryptoapi.com/v1'),
            'api_key' => env('FREE_CRYPTO_API_KEY', ''),
        ],
    ],
];
