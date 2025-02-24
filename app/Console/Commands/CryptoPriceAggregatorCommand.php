<?php

namespace App\Console\Commands;

use App\Jobs\FetchCryptoPrices;
use Illuminate\Console\Command;

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
     * Indicates if the command should stop.
     *
     * @var bool
     */
    protected $stop = false;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        pcntl_async_signals(true);

        pcntl_signal(SIGINT, function () {
            $this->stop = true;
        });

        pcntl_signal(SIGTERM, function () {
            $this->stop = true;
        });

        $interval = config('crypto.interval', 5);

        while (! $this->stop) {
            FetchCryptoPrices::dispatch();
            sleep($interval);
        }
    }
}
