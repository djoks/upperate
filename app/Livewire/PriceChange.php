<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class PriceChange extends Component
{
    public $id;

    public $price = [];

    public $change;

    #[On('echo:prices,CryptoPriceUpdated')]
    public function updateChange(array $eventData)
    {
        if ($this->price['exchange'] !== $eventData['exchange'] || $this->price['pair'] !== $eventData['pair']) {
            return;
        }

        $this->change = $eventData['price_change'];
        $this->dispatch('change-updated-'.$this->id, ['newChange' => $eventData['price_change']])->self();
    }

    public function mount($price)
    {
        $this->price = $price;
        $this->change = $price['price_change'];
        $this->id = uniqid();
    }

    public function render()
    {
        return view('livewire.price-change');
    }
}
