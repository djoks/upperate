<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Contracts\CryptoPriceServiceContract;

class HomePage extends Component
{
    protected CryptoPriceServiceContract $cryptoPriceService;

    public $headers = [
        ['key' => 'exchange', 'label' => 'Exchange'],
    ];

    public $subHeaders = [
        ['key' => 'pair', 'label' => 'Pair'],
        ['key' => 'average_price', 'label' => 'Average Price'],
        ['key' => 'price_change', 'label' => 'Price Change'],
        ['key' => 'updated_at', 'label' => 'Last Update'],
    ];

    public $exchanges = [];

    public function mount()
    {
        $this->cryptoPriceService = app(CryptoPriceServiceContract::class);
        $this->exchanges = $this->cryptoPriceService->getCryptoPrices();
    }

    public function render()
    {
        return view('livewire.pages.home');
    }
}
