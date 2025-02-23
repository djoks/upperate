<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class AveragePrice extends Component
{
    public $price = [];
    protected $listeners = [];

    public function priceUpdated()
    {
        //
    }

    public function mount($price)
    {
        $this->price = $price;
        $pair = strtolower($price['pair']);
        $exchange = strtolower($price['exchange']);
        $eventName = "echo:crypto.price";

        // Dynamically register the listener for this event.
        $this->listeners[$eventName] = 'handlePriceUpdate';
    }
    public function render()
    {
        return view('livewire.average-price');
    }

    public function handlePriceUpdate($data)
    {
        Log::debug('Price updated', $data);
        $this->price = $data;
    }
}
