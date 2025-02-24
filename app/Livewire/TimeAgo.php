<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;

class TimeAgo extends Component
{
    public $date = "";

    #[On('echo:prices,CryptoPriceUpdated')]
    public function refreshPrice(array $eventData)
    {
        $this->date = $eventData['date'];
    }

    public function mount($date)
    {
        $this->date = $date;
    }

    public function render()
    {
        return view('livewire.time-ago');
    }
}
