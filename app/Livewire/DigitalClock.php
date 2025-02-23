<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Carbon;

class DigitalClock extends Component
{
    /**
     * The current server time in ISO 8601 format.
     *
     * @var string
     */
    public $currentTime;

    /**
     * Mount the component.
     *
     * Initializes the current time using the server's time.
     *
     * @return void
     */
    public function mount(): void
    {
        $this->currentTime = Carbon::now()->toIso8601String();
    }

    /**
     * Update the current server time.
     *
     * This method is triggered via Livewire polling every 1000 milliseconds.
     *
     * @return void
     */
    public function updateTime(): void
    {
        $this->currentTime = Carbon::now()->toIso8601String();
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View The view for the Timer component.
     */
    public function render()
    {
        return view('livewire.digital-clock');
    }
}
