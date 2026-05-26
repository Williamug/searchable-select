<?php

namespace App\Livewire;

use App\Models\Country;
use Livewire\Component;

class Testsearchable extends Component
{
    public $selectedCountry = null;

    public $selectedFramework = null;

    public $frameworks = [];

    public function mount()
    {
        $this->frameworks = [
            [
                'id' => 1,
                'name' => 'Laravel',
            ],
            [
                'id' => 2,
                'name' => 'Livewire',
            ],
            [
                'id' => 3,
                'name' => 'Vue',
            ],
            [
                'id' => 4,
                'name' => 'React',
            ],
            [
                'id' => 5,
                'name' => 'AlpineJS',
            ],
        ];
    }

    public function render()
    {
        return view('livewire.testsearchable', [
            'countries' => Country::query()->get(),
        ]);
    }
}
