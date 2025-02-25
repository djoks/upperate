<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CryptoPriceController;
use Illuminate\Http\Request;
use App\Events\CryptoPriceUpdated;

Route::prefix('v1')->middleware('throttle:60,1')->group(function () {
    /**
     * Get the latest cryptocurrency prices.
     */
    Route::get('/crypto-prices', [CryptoPriceController::class, 'index']);

    /**
     * Test Broadcast a random cryptocurrency price update.
     */
    Route::get('/broadcast', function (Request $request) {
        // 1. Random integer >= 1000
        $averagePrice = mt_rand(1000, 999999);

        // 2. Random price change (positive or negative)
        // For instance, pick a random integer between -100 and +100
        $priceChange = mt_rand(-100, 100);

        // 3. Change direction based on sign of $priceChange
        $changeDirection = $priceChange >= 0 ? 'upward' : 'downward';

        $data = [
            'pair' => $request->get('pair', 'BTCUSDC'),
            'exchange' => $request->get('exchange', 'binance'),
            'average_price' => $averagePrice,
            'price_change' => $priceChange,
            'change_direction' => $changeDirection,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        event(new CryptoPriceUpdated($data));
        return response()->json(['status' => 'broadcasted']);
    });
});

