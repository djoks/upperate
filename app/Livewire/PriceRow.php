<?php

namespace App\Livewire;

use Livewire\Component;

class PriceRow extends Component
{
    public $price = [];
    protected $listeners = [];

    public function render()
    {
        return view('livewire.price-row');
    }
}
