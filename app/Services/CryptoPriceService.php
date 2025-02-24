<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;
use App\Events\CryptoPriceUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Promise\PromiseInterface;
use App\Contracts\CryptoPriceServiceContract;
use App\Contracts\CryptoPriceRepositoryContract;

/**
 * Class CryptoPriceService
 *
 * Service responsible for fetching and processing cryptocurrency price data.
 *
 * @package App\Services
 */
class CryptoPriceService implements CryptoPriceServiceContract
{
    /**
     * @var CryptoPriceRepositoryContract Repository for crypto price records.
     */
    protected $repo;

    /**
     * @var array List of crypto pairs from configuration.
     */
    protected $pairs;

    /**
     * @var array List of crypto exchanges from configuration.
     */
    protected $exchanges;

    /**
     * @var string Selected default API name from configuration.
     */
    protected $api;

    /**
     * @var string Base URL for the selected API.
     */
    protected $apiUrl;

    /**
     * @var string API key for authentication.
     */
    protected $apiKey;

    /**
     * CryptoPriceService constructor.
     *
     * @param CryptoPriceRepositoryContract $repo Repository instance injected.
     */
    public function __construct(CryptoPriceRepositoryContract $repo)
    {
        $this->repo = $repo;
        $this->pairs = config('crypto.pairs', []);
        $this->exchanges = config('crypto.exchanges', []);
        $this->api = config('crypto.apis.default');
        $this->apiUrl = config('crypto.apis.' . $this->api . '.api_url');
        $this->apiKey = config('crypto.apis.' . $this->api . '.api_key');
    }

    /**
     * Computes the average price for a pair.
     *
     * Calculates the mean of the 'lowest' and 'highest' price values provided in the data array.
     *
     * @param array $data Array containing at least the 'lowest' and 'highest' keys.
     * @return float The computed average price.
     */
    public function computeAveragePrice(array $data): float
    {
        $lowest  = (float) $data['lowest'];
        $highest = (float) $data['highest'];
        return ($lowest + $highest) / 2;
    }

    /**
     * Retrieves the configured cryptocurrency pairs.
     *
     * @return array List of crypto pairs.
     */
    public function getPairs(): array
    {
        return $this->pairs;
    }

    /**
     * Retrieves the configured cryptocurrency exchanges.
     *
     * @return array List of crypto exchanges.
     */
    public function getExchanges(): array
    {
        return $this->exchanges;
    }

    /**
     * Retrieves all crypto price records.
     *
     * @return array An array of crypto price records.
     */
    public function getCryptoPrices(): array
    {
        return $this->repo->getLatestRecordsGroupedByExchange();
    }

    /**
     * Builds the API query URL for a given exchange.
     *
     * The URL is constructed in the following format:
     *   {apiUrl}/getData?symbol=pair1+pair2+...+pairN@exchange
     *
     * For example:
     *   https://api.freecryptoapi.com/v1/getData?symbol=ETHBTC+BTCUSDC@huobi
     *
     * @param string $pair The pair identifier.
     * @param string $exchange The exchange identifier.
     * @return string The complete API URL for fetching data.
     */
    public function buildApiQuery(string $pair, string $exchange): string
    {
        return $this->apiUrl . '/getData?symbol=' . $pair . '@' . $exchange;
    }

    /**
     * Fetches API data from a given URL.
     *
     * Sends an HTTP GET request to the provided URL and returns the JSON response as an associative array
     * if the request is successful; otherwise, returns null.
     *
     * @param string $url The API URL.
     * @return array|null The response data or null if the request was unsuccessful.
     */
    protected function fetchApiData(string $url): ?array
    {
        $response = Http::withToken($this->apiKey)->get($url);

        return $response->successful() ? $response->json() : null;
    }

    /**
     * Processes the API response for a specific exchange.
     *
     * Checks whether the response indicates success and contains a 'symbols' array. If valid,
     * iterates over each symbol's data and processes it via compareAndSave().
     * Otherwise, logs a warning.
     *
     * @param array $data The API response data.
     * @param string $exchange The exchange identifier.
     * @return void
     */
    protected function processApiResponse(array $data, string $exchange): void
    {
        if (isset($data['status'], $data['symbols']) && $data['status'] === 'success' && is_array($data['symbols'])) {
            foreach ($data['symbols'] as $symbolData) {
                $this->compareAndSave($symbolData, $exchange);
            }
        } else {
            Log::warning("API response not successful for exchange: {$exchange}");
        }
    }

    /**
     * Asynchronously fetch API data using Guzzle.
     *
     * @param string $url
     * @return PromiseInterface
     */
    protected function fetchApiDataAsync(string $url): PromiseInterface
    {
        $client = new Client();

        return $client->getAsync($url, [
            'headers' => ['Authorization' => 'Bearer ' . $this->apiKey]
        ])
            ->then(function (\Psr\Http\Message\ResponseInterface $response) {
                if ($response->getStatusCode() !== 200) {
                    Log::error("Received non-200 response: " . $response->getStatusCode());
                    return null;
                }
                $body = $response->getBody()->getContents();
                return json_decode($body, true);
            })
            ->otherwise(function (\Exception $e) {
                Log::error("Async API call failed: " . $e->getMessage());
                return null;
            });
    }

    /**
     * Processes cryptocurrency data by iterating over each configured exchange.
     *
     * For each exchange, the method:
     * - Constructs the API query URL.
     * - Fetches data from the API.
     * - Processes the API response.
     *
     * If data fetching fails, an error is logged.
     *
     * @return void
     */
    public function fetchAndSaveCryptoPrices(): void
    {
        $promises[] = [];

        foreach ($this->exchanges as $exchange) {
            foreach ($this->pairs as $pair) {
                $url = $this->buildApiQuery($pair, $exchange);

                $promises[] = $this->fetchApiDataAsync($url)
                    ->then(function ($data) use ($exchange, $pair, $url) {
                        if ($data) {
                            $this->processApiResponse($data, $exchange);
                        } else {
                            Log::error("Failed to fetch data for exchange: {$exchange}, pair: {$pair} from URL: {$url}");
                        }
                    });
            }
        }

        Utils::settle($promises)->wait();
    }

    /**
     * Compares new symbol data with the last known record and saves an updated record if necessary.
     *
     * For the given symbol data, computes the new average price and compares it with the last stored value.
     * If there is a difference, calculates the price change and determines the change direction
     * (either 'upward' or 'downward') before saving the new record.
     *
     * @param array $data The symbol data from the API, including 'symbol', 'lowest', 'highest',
     *                    and 'daily_change_percentage'.
     * @param string $exchange The exchange identifier.
     * @return void
     */
    protected function compareAndSave(array $data, string $exchange): void
    {
        $pair = $data['symbol'];
        $newAverage = $this->computeAveragePrice($data);
        $lastRecord = $this->repo->getLastKnownPrice($pair, $exchange);

        if ($lastRecord) {
            $lastAverage = (float) $lastRecord->average_price;

            if ($newAverage == $lastAverage) {
                return;
            }

            $priceChange = $data['daily_change_percentage'];
            $changeDirection = $priceChange > $lastRecord->price_change ? 'upward' : 'downward';
        } else {
            $priceChange = 0;
            $changeDirection = 'upward';
        }

        $data = [
            'pair' => $pair,
            'exchange' => $exchange,
            'average_price' => $newAverage,
            'price_change' => $priceChange,
            'change_direction' => $changeDirection,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $this->repo->save($data);

        event(new CryptoPriceUpdated($data));
    }
}
