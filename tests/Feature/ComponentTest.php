<?php

use Livewire\Component;
use Livewire\Livewire;

// Component is now loaded from vendor, no installation needed

test('it renders the component', function () {
  $component = Livewire::test(TestComponent::class);

  $component->assertSee('Select option');
});

test('it displays custom placeholder', function () {
  $component = Livewire::test(TestComponentWithCustomPlaceholder::class);

  $component->assertSee('Choose a country');
});

test('it displays selected value', function () {
  $component = Livewire::test(TestComponentWithSelectedValue::class);

  $component->assertSee('United States');
});

test('it updates value on selection', function () {
  $component = Livewire::test(TestComponent::class);

  $component->call('$set', 'selected_country', 1)
    ->assertSet('selected_country', 1);
});

// Test Livewire Components
class TestComponent extends Component
{
  public $countries;

  public $selected_country;

  public function mount()
  {
    $this->countries = collect([
      ['id' => 1, 'name' => 'United States'],
      ['id' => 2, 'name' => 'Canada'],
      ['id' => 3, 'name' => 'Mexico'],
    ]);
  }

  public function render()
  {
    return <<<'HTML'
        <div>
            <x-searchable-select
                :options="$countries"
                wire-model="selected_country"
                :selected-value="$selected_country"
            />
        </div>
        HTML;
  }
}

class TestComponentWithCustomPlaceholder extends Component
{
  public $countries;

  public $selected_country;

  public function mount()
  {
    $this->countries = collect([
      ['id' => 1, 'name' => 'United States'],
    ]);
  }

  public function render()
  {
    return <<<'HTML'
        <div>
            <x-searchable-select
                :options="$countries"
                wire-model="selected_country"
                :selected-value="$selected_country"
                placeholder="Choose a country"
            />
        </div>
        HTML;
  }
}

class TestComponentWithSelectedValue extends Component
{
  public $countries;

  public $selected_country = 1;

  public function mount()
  {
    $this->countries = collect([
      ['id' => 1, 'name' => 'United States'],
      ['id' => 2, 'name' => 'Canada'],
    ]);
  }

  public function render()
  {
    return <<<'HTML'
        <div>
            <x-searchable-select
                :options="$countries"
                wire-model="selected_country"
                :selected-value="$selected_country"
            />
        </div>
        HTML;
  }
}
