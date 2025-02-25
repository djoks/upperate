<?php

namespace App\Http\Controllers;

use App\Contracts\CryptoPriceServiceContract;
use App\Http\Resources\CryptoExchangeResource;

class CryptoPriceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param CryptoPriceServiceContract $cryptoPriceService
     */
    public function __construct(protected CryptoPriceServiceContract $cryptoPriceService)
    {
    }

    public function index()
    {
        $cryptoPrices = $this->cryptoPriceService->getCryptoPrices();

        return CryptoExchangeResource::collection($cryptoPrices);

    }
}
