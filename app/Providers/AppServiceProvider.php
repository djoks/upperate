<?php

namespace App\Providers;

use App\Contracts\CryptoPriceRepositoryContract;
use App\Contracts\CryptoPriceServiceContract;
use App\Repositories\CryptoPriceRepository;
use App\Services\CryptoPriceService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CryptoPriceRepositoryContract::class, CryptoPriceRepository::class);
        $this->app->bind(CryptoPriceServiceContract::class, CryptoPriceService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
