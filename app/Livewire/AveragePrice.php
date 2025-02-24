<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class AveragePrice extends Component
{
    public $price = [];
    public $pair;
    public $exchange;

    #[On('echo:prices,CryptoPriceUpdated')]
    public function refreshPrice(array $eventData)
    {
        $this->price = $eventData;
    }

    public function mount($price)
    {
        $this->price = $price;
        $this->pair = strtolower($price['pair']);
        $this->exchange = strtolower($price['exchange']);
    }

    public function render()
    {
        return view('livewire.average-price');
    }
}
