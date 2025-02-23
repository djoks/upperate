<?php

namespace App\Contracts;

use App\Models\CryptoPrice;

interface CryptoPriceRepositoryContract
{
    /**
     * Retrieve a CryptoPrice record by its unique identifier.
     *
     * @param int $id The unique identifier of the CryptoPrice record.
     * @return CryptoPrice|null The CryptoPrice record, or null if not found.
     */
    public function findById(int $id): ?CryptoPrice;

    /**
     * Retrieve all CryptoPrice records.
     *
     * @return array An array of all CryptoPrice records.
     */
    public function getAll(): array;

    /**
     * Retrieves the latest record for each unique exchange and pair combination,
     * grouped by exchange.
     *
     * This method performs the following steps:
     * 1. Obtains the maximum (latest) created_at timestamp for each (exchange, pair) group.
     * 2. Retrieves the full record corresponding to that timestamp.
     * 3. Groups the resulting records by exchange.
     *
     * @return array An array of groups, each containing:
     * - 'exchange': The exchange name.
     * - 'prices': An array of the latest CryptoPrice records for that exchange.
     */
    public function getLatestRecordsGroupedByExchange(): array;

    /**
     * Retrieve the last known CryptoPrice record for a specified cryptocurrency pair and exchange.
     *
     * @param string $pair The cryptocurrency pair (e.g., "BTCUSDC").
     * @param string $exchange The exchange name (e.g., "binance").
     * @return CryptoPrice|null The latest CryptoPrice record for the specified pair and exchange, or null if not found.
     */
    public function getLastKnownPrice(string $pair, string $exchange): ?CryptoPrice;

    /**
     * Save a new CryptoPrice record.
     *
     * The provided data array should contain the following keys:
     * - 'pair': The cryptocurrency pair.
     * - 'exchange': The exchange name.
     * - 'average_price': The computed average price.
     * - 'price_change': The price change value.
     * - 'change_direction': The direction of the price change ('upward' or 'downward').
     * - 'created_at': Timestamp for record creation.
     * - 'updated_at': Timestamp for record update.
     *
     * @param array $data An associative array of CryptoPrice record data.
     * @return CryptoPrice The newly created CryptoPrice record.
     */
    public function save(array $data): CryptoPrice;
}
