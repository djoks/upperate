<?php

namespace App\Livewire;

use Livewire\Component;

class ExchangeLogo extends Component
{
    public $exchange;

    public function mount($exchange)
    {
        $this->exchange = $exchange;
    }

    public function render()
    {
        return view('livewire.exchange-logo');
    }
}
