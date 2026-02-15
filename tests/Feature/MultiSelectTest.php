<?php

use Illuminate\Support\Facades\File;
use Livewire\Livewire;
use Livewire\Component;

beforeEach(function () {
  // Install the component for testing
  $this->artisan('install:searchable-select')->run();
});

afterEach(function () {
  // Clean up
  $componentPath = resource_path('views/components/searchable-select.blade.php');
  if (File::exists($componentPath)) {
    File::delete($componentPath);
  }
});

test('it supports multi-select mode', function () {
  $component = Livewire::test(MultiSelectComponent::class);

  expect($component->get('selected_countries'))->toBeArray();
  expect($component->get('selected_countries'))->toBeEmpty();
});

test('it can select multiple values', function () {
  $component = Livewire::test(MultiSelectComponent::class);

  $component->set('selected_countries', [1, 2]);

  expect($component->get('selected_countries'))
    ->toBeArray()
    ->toHaveCount(2)
    ->toContain(1)
    ->toContain(2);
});

test('it displays selected values as tags', function () {
  $component = Livewire::test(MultiSelectComponent::class)
    ->set('selected_countries', [1, 2]);

  $component->assertSee('United States')
    ->assertSee('Canada');
});

test('it can remove individual selections', function () {
  $component = Livewire::test(MultiSelectComponent::class)
    ->set('selected_countries', [1, 2, 3]);

  // Remove one item
  $component->set('selected_countries', [1, 3]);

  expect($component->get('selected_countries'))
    ->toHaveCount(2)
    ->toContain(1)
    ->toContain(3)
    ->not->toContain(2);
});

test('it shows clear button when values selected', function () {
  $component = Livewire::test(ClearableComponent::class)
    ->set('country_id', 1);

  $html = $component->html();
  expect($html)->toContain('@click.stop="clearAll()');
});

test('it can clear all selections', function () {
  $component = Livewire::test(ClearableComponent::class)
    ->set('country_id', 1);

  $component->set('country_id', null);

  expect($component->get('country_id'))->toBeNull();
});

test('it can clear multiple selections', function () {
  $component = Livewire::test(MultiSelectComponent::class)
    ->set('selected_countries', [1, 2, 3]);

  $component->set('selected_countries', []);

  expect($component->get('selected_countries'))->toBeEmpty();
});

// Test Livewire Components
class MultiSelectComponent extends Component
{
  public $countries;
  public $selected_countries = [];

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
                wire-model="selected_countries"
                :selected-value="$selected_countries"
                :multiple="true"
                placeholder="Select Countries"
            />
        </div>
        HTML;
  }
}

class ClearableComponent extends Component
{
  public $countries;
  public $country_id;

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
                wire-model="country_id"
                :selected-value="$country_id"
                :clearable="true"
            />
        </div>
        HTML;
  }
}
