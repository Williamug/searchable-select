<?php

use Livewire\Component;
use Livewire\Livewire;

// Component is now loaded from vendor, no installation needed

test('it renders grouped options', function () {
    $component = Livewire::test(GroupedOptionsComponent::class);

    $component->assertSee('North America')
        ->assertSee('Europe')
        ->assertSee('United States')
        ->assertSee('France');
});

test('it can select from grouped options', function () {
    $component = Livewire::test(GroupedOptionsComponent::class);

    $component->set('country_id', 1);

    expect($component->get('country_id'))->toBe(1);
});

test('it displays group labels correctly', function () {
    $component = Livewire::test(GroupedOptionsComponent::class);

    $html = $component->html();

    expect($html)->toContain('North America');
    expect($html)->toContain('Europe');
});

// Test Livewire Component
class GroupedOptionsComponent extends Component
{
    public $locations;

    public $country_id;

    public function mount()
    {
        $this->locations = [
            [
                'label' => 'North America',
                'options' => [
                    ['id' => 1, 'name' => 'United States'],
                    ['id' => 2, 'name' => 'Canada'],
                    ['id' => 3, 'name' => 'Mexico'],
                ],
            ],
            [
                'label' => 'Europe',
                'options' => [
                    ['id' => 4, 'name' => 'United Kingdom'],
                    ['id' => 5, 'name' => 'France'],
                    ['id' => 6, 'name' => 'Germany'],
                ],
            ],
        ];
    }

    public function render()
    {
        return <<<'HTML'
        <div>
            <x-searchable-select
                :options="$locations"
                wire-model="country_id"
                :selected-value="$country_id"
                :grouped="true"
                placeholder="Select Country"
            />
        </div>
        HTML;
    }
}
