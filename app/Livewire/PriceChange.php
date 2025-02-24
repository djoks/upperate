<?php

namespace App\Livewire;

use Livewire\Component;

class PriceChange extends Component
{
    public $change = 0;

    public function mount($change)
    {
        $this->change = $change;
    }

    public function render()
    {
        return view('livewire.price-change');
    }
}
