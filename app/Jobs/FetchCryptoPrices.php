<?php

namespace App\Jobs;

use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Contracts\CryptoPriceServiceContract;

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
