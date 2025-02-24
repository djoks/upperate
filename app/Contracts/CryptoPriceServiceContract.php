<?php

namespace App\Contracts;

interface CryptoPriceServiceContract
{
    /**
     * Computes the average price for a pair.
     *
     * Calculates the mean of the 'lowest' and 'highest' price values provided in the data array.
     *
     * @param  array  $data  Array containing at least the 'lowest' and 'highest' keys.
     * @return float The computed average price.
     */
    public function computeAveragePrice(array $data): float;

    /**
     * Retrieves the configured cryptocurrency pairs.
     *
     * @return array List of crypto pairs.
     */
    public function getPairs(): array;

    /**
     * Retrieves the configured cryptocurrency exchanges.
     *
     * @return array List of crypto exchanges.
     */
    public function getExchanges(): array;

    /**
     * Retrieves all crypto price records.
     *
     * @return array An array of crypto price records.
     */
    public function getCryptoPrices(): array;

    /**
     * Builds the API query URL for a given exchange.
     *
     * The URL is constructed in the following format:
     *   {apiUrl}/getData?symbol=pair1+pair2+...+pairN@exchange
     *
     * For example:
     *   https://api.freecryptoapi.com/v1/getData?symbol=ETHBTC+BTCUSDC@huobi
     *
     * @param  string  $pair  The pair identifier.
     * @param  string  $exchange  The exchange identifier.
     * @return string The complete API URL for fetching data.
     */
    public function buildApiQuery(string $pair, string $exchange): string;

    /**
     * Compares new symbol data with the last known record and saves an updated record if necessary.
     *
     * For the given symbol data, computes the new average price and compares it with the last stored value.
     * If there is a difference, calculates the price change and determines the change direction
     * (either 'upward' or 'downward') before saving the new record.
     */
    public function fetchAndSaveCryptoPrices(): void;
}
