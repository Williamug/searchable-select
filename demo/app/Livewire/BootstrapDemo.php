<?php

namespace App\Livewire;

use Livewire\Component;

class BootstrapDemo extends Component
{
    public $countries;

    public $country_id;

    public $selected_cities = [];

    public function mount()
    {
        $this->countries = [
            ['id' => 1, 'name' => 'United States'],
            ['id' => 2, 'name' => 'Canada'],
            ['id' => 3, 'name' => 'United Kingdom'],
            ['id' => 4, 'name' => 'Australia'],
            ['id' => 5, 'name' => 'Germany'],
        ];
    }

    public function render()
    {
        return view('livewire.bootstrap-demo');
    }
}
