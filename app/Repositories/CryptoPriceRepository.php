<?php

namespace App\Repositories;

use App\Contracts\CryptoPriceRepositoryContract;
use App\Models\CryptoPrice;
use Illuminate\Support\Facades\DB;

/**
 * Class CryptoPriceRepository
 *
 * Repository for managing CryptoPrice records.
 *
 * This class provides methods to retrieve, search, and save cryptocurrency price
 * records as well as to fetch the latest records grouped by exchange.
 */
class CryptoPriceRepository implements CryptoPriceRepositoryContract
{
    /**
     * Retrieve a CryptoPrice record by its unique identifier.
     *
     * @param  int  $id  The unique identifier of the CryptoPrice record.
     * @return CryptoPrice|null The CryptoPrice record, or null if not found.
     */
    public function findById(int $id): ?CryptoPrice
    {
        return CryptoPrice::find($id);
    }

    /**
     * Retrieve all CryptoPrice records.
     *
     * @return array An array of all CryptoPrice records.
     */
    public function getAll(): array
    {
        return CryptoPrice::all()->toArray();
    }

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
     *               - 'exchange': The exchange name.
     *               - 'prices': An array of the latest CryptoPrice records for that exchange.
     */
    public function getLatestRecordsGroupedByExchange(): array
    {
        // 1. Get the latest created_at for each exchange + pair
        $latestTimes = CryptoPrice::select('exchange', 'pair', DB::raw('MAX(created_at) as latest_created_at'))
            ->groupBy('exchange', 'pair');

        // 2. For each group above, get the record with the MAX(id) to break ties
        $latestIds = CryptoPrice::select(DB::raw('MAX(id) as latest_id'))
            ->groupBy('exchange', 'pair', 'created_at');

        // 3. Join the two subqueries to get the full records
        $records = CryptoPrice::joinSub($latestTimes, 'latest_times', function ($join) {
            $join->on('crypto_prices.exchange', '=', 'latest_times.exchange')
                ->on('crypto_prices.pair', '=', 'latest_times.pair')
                ->on('crypto_prices.created_at', '=', 'latest_times.latest_created_at');
        })
            ->whereIn('crypto_prices.id', $latestIds)
            ->get();

        // Group results by exchange
        $grouped = $records->groupBy('exchange')->map(function ($group, $exchange) {
            return [
                'exchange' => $exchange,
                'prices' => $group->values()->toArray(),
            ];
        })->values()->toArray();

        return $grouped;
    }

    /**
     * Retrieve the last known CryptoPrice record for a specified cryptocurrency pair and exchange.
     *
     * @param  string  $pair  The cryptocurrency pair (e.g., "BTCUSDC").
     * @param  string  $exchange  The exchange name (e.g., "binance").
     * @return CryptoPrice|null The latest CryptoPrice record for the specified pair and exchange, or null if not found.
     */
    public function getLastKnownPrice(string $pair, string $exchange): ?CryptoPrice
    {
        return CryptoPrice::where('pair', $pair)
            ->where('exchange', $exchange)
            ->orderBy('created_at', 'desc')
            ->first();
    }

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
     * @param  array  $data  An associative array of CryptoPrice record data.
     * @return CryptoPrice The newly created CryptoPrice record.
     */
    public function save(array $data): CryptoPrice
    {
        return CryptoPrice::create($data);
    }
}
