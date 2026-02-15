<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class ApiExample extends Component
{
    public $country_id;

    public function render()
    {
        return view('livewire.api-example');
    }
}
