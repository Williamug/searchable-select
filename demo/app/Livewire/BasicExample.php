<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class BasicExample extends Component
{
  public $country_id;
  public $countries;

  public function mount()
  {
    $this->countries = [
      ['value' => 1, 'label' => 'United States'],
      ['value' => 2, 'label' => 'United Kingdom'],
      ['value' => 3, 'label' => 'Canada'],
      ['value' => 4, 'label' => 'Australia'],
      ['value' => 5, 'label' => 'Germany'],
      ['value' => 6, 'label' => 'France'],
      ['value' => 7, 'label' => 'Italy'],
      ['value' => 8, 'label' => 'Spain'],
      ['value' => 9, 'label' => 'Japan'],
      ['value' => 10, 'label' => 'China'],
    ];
  }

  public function render()
  {
    return view('livewire.basic-example');
  }
}
