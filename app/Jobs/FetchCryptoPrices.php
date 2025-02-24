<?php

namespace App\Jobs;

use App\Contracts\CryptoPriceServiceContract;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchCryptoPrices implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(CryptoPriceServiceContract $cryptoPriceService): void
    {
        $cryptoPriceService->fetchAndSaveCryptoPrices();
    }
}
