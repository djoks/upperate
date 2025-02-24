<?php

use App\Livewire\Pages\HomePage;
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

Route::get('/update', function () {
    $data = [
        'pair' => "BTCUSDC",
        'exchange' => "binance",
        'average_price' => 0,
        'price_change' => 50,
        'change_direction' => "upward",
        'created_at' => now(),
        'updated_at' => now(),
    ];

    event(new App\Events\CryptoPriceUpdated($data));
    // App\Events\CryptoPriceUpdated::dispatch($data);
});
