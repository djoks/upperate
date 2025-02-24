<?php

use App\Livewire\Pages\HomePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', HomePage::class);

/**
 * Test Broadcast a random cryptocurrency price update.
 */
Route::get('/broadcast', function (Request $request) {
    // 1. Random integer >= 1000
    $averagePrice = rand(1000, 999999);

    // 2. Random price change (positive or negative)
    // For instance, pick a random integer between -100 and +100
    $priceChange = rand(-100, 100);

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

    event(new App\Events\CryptoPriceUpdated($data));
});
