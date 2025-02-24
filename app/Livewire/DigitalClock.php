<?php

namespace App\Livewire;

use Livewire\Component;

class DigitalClock extends Component
{
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
