@php
namespace App\Livewire;

use Livewire\Component;

class GroupedExample extends Component
{
public $country_id;
public $countries;

public function mount()
{
$this->countries = [
['value' => 1, 'label' => 'United States', 'group' => 'North America'],
['value' => 2, 'label' => 'Canada', 'group' => 'North America'],
['value' => 3, 'label' => 'Mexico', 'group' => 'North America'],
['value' => 4, 'label' => 'United Kingdom', 'group' => 'Europe'],
['value' => 5, 'label' => 'Germany', 'group' => 'Europe'],
['value' => 6, 'label' => 'France', 'group' => 'Europe'],
['value' => 7, 'label' => 'Italy', 'group' => 'Europe'],
['value' => 8, 'label' => 'Japan', 'group' => 'Asia'],
['value' => 9, 'label' => 'China', 'group' => 'Asia'],
['value' => 10, 'label' => 'India', 'group' => 'Asia'],
];
}

public function render()
{
return view('livewire.grouped-example');
}
}
@endphp
