<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class DateChanged extends Component
{
    public $id;

    public $price;

    public $date;

    #[On('echo:prices,CryptoPriceUpdated')]
    public function updateChange(array $eventData)
    {
        if ($this->price['exchange'] !== $eventData['exchange'] || $this->price['pair'] !== $eventData['pair']) {
            return;
        }

        $this->date = $eventData['updated_at'];
        $this->dispatch('date-updated-'.$this->id, ['newDate' => $eventData['updated_at']])->self();
    }

    public function mount($price)
    {
        $this->id = uniqid();
        $this->price = $price;
        $this->date = $price['updated_at'];
    }

    public function render()
    {
        return view('livewire.date-changed');
    }
}
