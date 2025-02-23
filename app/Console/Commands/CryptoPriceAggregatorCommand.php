<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\FetchCryptoPrices;

class CryptoPriceAggregatorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto:aggregate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggregate crypto prices and dispatch jobs at a configured interval.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $interval = config('crypto.interval', 5);
        $this->info("Running 'Crypto Price Aggregator' at an interval of: {$interval} seconds...");

        while (true) {
            FetchCryptoPrices::dispatch();
            sleep($interval);
        }
    }
}
