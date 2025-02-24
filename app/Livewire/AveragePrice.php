<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class AveragePrice extends Component
{
    public $id;

    public $price = [];

    public $pair;

    public $exchange;

    #[On('echo:prices,CryptoPriceUpdated')]
    public function updatePrice(array $eventData)
    {
        if ($this->price['exchange'] !== $eventData['exchange'] || $this->price['pair'] !== $eventData['pair']) {
            return;
        }

        $this->price = $eventData;
        $this->dispatch('price-updated-'.$this->id, ['newPrice' => $eventData['average_price']])->self();
    }

    public function mount($price)
    {
        $this->id = uniqid();
        $this->price = $price;
        $this->pair = strtolower($price['pair']);
        $this->exchange = strtolower($price['exchange']);
    }

    public function render()
    {
        return view('livewire.average-price');
    }
}
