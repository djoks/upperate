<?php

namespace App\Livewire\Pages;

use App\Contracts\CryptoPriceServiceContract;
use Livewire\Component;

class HomePage extends Component
{
    protected CryptoPriceServiceContract $cryptoPriceService;

    public array $expanded = [];

    public array $headers = [
        ['key' => 'exchange', 'label' => 'Exchange'],
    ];

    public array $subHeaders = [
        ['key' => 'pair', 'label' => 'Pair'],
        ['key' => 'average_price', 'label' => 'Average Price'],
        ['key' => 'price_change', 'label' => 'Price Change'],
        ['key' => 'updated_at', 'label' => 'Last Update'],
    ];

    public array $exchanges = [];

    public function mount()
    {
        $this->cryptoPriceService = app(CryptoPriceServiceContract::class);
        $this->exchanges = $this->cryptoPriceService->getCryptoPrices();
        $this->expanded = $this->cryptoPriceService->getExchanges();
    }

    public function render()
    {
        return view('livewire.pages.home');
    }
}
