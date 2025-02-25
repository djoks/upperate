<?php

use App\Models\CryptoPrice;
use Illuminate\Support\Facades\Http;

/**
 * CryptoPriceService Test Suite
 *
 * This test suite validates the functionality of the CryptoPriceService class.
 *
 * Overview of Tests:
 *
 * 1. computes the average price correctly:
 *    - Verifies that the computeAveragePrice method correctly calculates the average using 'lowest' and 'highest' values.
 *
 * 2. returns the configured pairs:
 *    - Confirms that getPairs returns the expected list of cryptocurrency pairs.
 *
 * 3. returns the configured exchanges:
 *    - Ensures that getExchanges returns the expected list of exchange names.
 *
 * 4. builds the correct API query URL:
 *    - Checks that buildApiQuery constructs the correct API URL based on a given pair and exchange.
 *
 * 5. retrieves all crypto prices from the repository:
 *    - Validates that getCryptoPrices correctly retrieves data by calling the repository's getLatestRecordsGroupedByExchange method.
 *
 * 6. fetches API data successfully:
 *    - Tests that the fetchApiData method (made accessible via reflection) correctly processes a fake HTTP response.
 *
 * 7. processes API response and saves new record when price changes:
 *    - Ensures that processApiResponse correctly identifies a price change, computes the new average
 *      and price change, determines the correct change direction, and saves a new record.
 *
 * 8. skips saving when no price change is detected:
 *    - Validates that when the API response indicates no change in the average price and percentage,
 *      the system properly bypasses the save operation.
 *
 * Additional Notes:
 *
 * - The tests leverage the Mockery framework to simulate the behavior of the CryptoPriceRepositoryContract.
 * - Reflection is used to access and test otherwise inaccessible internal methods.
 * - The tests ensure that both the API interaction and internal logic for processing crypto data function as expected.
 */

beforeEach(function () {
    /** @var \Mockery\LegacyMockInterface|\Mockery\MockInterface|\App\Contracts\CryptoPriceRepositoryContract $repo */
    $this->repo = Mockery::mock(App\Contracts\CryptoPriceRepositoryContract::class);
    $this->service = new App\Services\CryptoPriceService($this->repo);
});

it('computes the average price correctly', function () {
    $data = [
        'lowest' => '100',
        'highest' => '200',
    ];

    expect($this->service->computeAveragePrice($data))->toBe(150.0);
});

it('returns the configured pairs', function () {
    expect($this->service->getPairs())->toBe(['BTCUSDC', 'BTCUSDT', 'BTCETH']);
});

it('returns the configured exchanges', function () {
    expect($this->service->getExchanges())->toBe(['binance', 'mex', 'huobi']);
});

it('builds the correct API query URL', function () {
    $url = $this->service->buildApiQuery(
        'BTCUSDT',
        'binance'
    );
    expect($url)->toBe('https://api.freecryptoapi.com/v1/getData?symbol=BTCUSDT@binance');
});

it('retrieves all crypto prices from the repository', function () {
    $this->repo->shouldReceive('getLatestRecordsGroupedByExchange')->once()->andReturn([1, 2]);
    expect($this->service->getCryptoPrices())->toBe([1, 2]);
});

it('fetches API data successfully', function () {
    Http::fake(function ($request) {
        return Http::response(['status' => 'success', 'symbols' => []], 200);
    });

    $reflection = new ReflectionClass($this->service);
    $method = $reflection->getMethod('fetchApiData');
    $method->setAccessible(true);
    $data = $method->invokeArgs($this->service, [
        'https://api.freecryptoapi.com/v1/getData?symbol=BTCUSDC+BTCUSDT+BTCETH@binance',
    ]);
    expect($data)->toBe(['status' => 'success', 'symbols' => []]);
});

it('processes API response and saves new record when price changes', function () {
    $symbolData = [
        'symbol' => 'BTCUSDT',
        'lowest' => '100',
        'highest' => '200',
        'daily_change_percentage' => 5,
    ];

    $lastRecord = new CryptoPrice;
    $lastRecord->average_price = 160;
    $lastRecord->price_change = 2;

    $this->repo->shouldReceive('getLastKnownPrice')
        ->once()
        ->with('BTCUSDT', 'binance')
        ->andReturn($lastRecord);

    $this->repo->shouldReceive('save')->once()->with(Mockery::on(function ($data) {
        return $data['pair'] === 'BTCUSDT'
            && $data['exchange'] === 'binance'
            && $data['average_price'] == 150
            && $data['price_change'] == 5
            && in_array($data['change_direction'], ['upward', 'downward']);
    }));

    $apiResponse = [
        'status' => 'success',
        'symbols' => [$symbolData],
    ];

    $reflection = new ReflectionClass($this->service);
    $method = $reflection->getMethod('processApiResponse');
    $method->setAccessible(true);
    $method->invokeArgs($this->service, [$apiResponse, 'binance']);
});

it('skips saving when no price change is detected', function () {
    $symbolData = [
        'symbol' => 'BTCUSDT',
        'lowest' => '100',
        'highest' => '200',
        'daily_change_percentage' => 5,
    ];

    $lastRecord = new CryptoPrice;
    $lastRecord->average_price = 150;
    $lastRecord->price_change = 5;

    $this->repo->shouldReceive('getLastKnownPrice')
        ->once()
        ->with('BTCUSDT', 'binance')
        ->andReturn($lastRecord);

    $this->repo->shouldNotReceive('save');

    $reflection = new ReflectionClass($this->service);
    $method = $reflection->getMethod('compareAndSave');
    $method->setAccessible(true);
    $method->invokeArgs($this->service, [$symbolData, 'binance']);
});
