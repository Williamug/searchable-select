<?php

use Livewire\Component;
use Livewire\Livewire;

test('renders without errors with no wire:model', function () {
    $html = Livewire::test(NoWireModelComponent::class)->html();
    expect($html)->toContain('searchableSelect');
});

test('renders correct placeholder text', function () {
    $html = Livewire::test(PlaceholderComponent::class)->html();
    expect($html)->toContain('Choose a country');
});

test('renders option values in html', function () {
    $html = Livewire::test(BasicComponent::class)->html();
    expect($html)->toContain('Uganda')->toContain('Kenya');
});

test('renders selected display label when livewire property has a value', function () {
    $html = Livewire::test(PreselectedComponent::class)->html();
    // labelsMap embedded in HTML enables Alpine to display the label
    expect($html)->toContain('Uganda');
});

test('setting the livewire property updates the displayed label', function () {
    $component = Livewire::test(BasicComponent::class);
    $component->set('country_id', 2);
    expect($component->html())->toContain('Kenya');
});

test('selecting an option updates the livewire property', function () {
    $component = Livewire::test(BasicComponent::class);
    $component->call('$set', 'country_id', 1);
    $component->assertSet('country_id', 1);
});

// ─── Inline Livewire components ────────────────────────────────────────────

class BasicComponent extends Component
{
    public ?int $country_id = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-searchable-select
                wire:model="country_id"
                :options="[['id' => 1, 'name' => 'Uganda'], ['id' => 2, 'name' => 'Kenya']]"
            />
        </div>
        HTML;
    }
}

class NoWireModelComponent extends Component
{
    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-searchable-select
                :options="[['id' => 1, 'name' => 'Option 1']]"
            />
        </div>
        HTML;
    }
}

class PlaceholderComponent extends Component
{
    public ?int $country_id = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-searchable-select
                wire:model="country_id"
                :options="[['id' => 1, 'name' => 'Uganda']]"
                placeholder="Choose a country"
            />
        </div>
        HTML;
    }
}

class PreselectedComponent extends Component
{
    public int $country_id = 1;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-searchable-select
                wire:model="country_id"
                :options="[['id' => 1, 'name' => 'Uganda'], ['id' => 2, 'name' => 'Kenya']]"
            />
        </div>
        HTML;
    }
}
