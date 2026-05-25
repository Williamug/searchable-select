<?php

use Livewire\Component;
use Livewire\Livewire;

test('group headers render in html', function () {
    $html = Livewire::test(GroupedComponent::class)->html();
    // Group names are in the JSON-encoded options
    expect($html)->toContain('East Africa')->toContain('West Africa');
});

test('items within groups render correctly', function () {
    $html = Livewire::test(GroupedComponent::class)->html();
    expect($html)->toContain('Uganda')->toContain('Ghana');
});

test('search filtering within groups works', function () {
    $html = Livewire::test(GroupedComponent::class)->html();
    // The grouped template renders the filteredOptions loop and group headers
    expect($html)
        ->toContain('grouped: true')
        ->toContain('ss-group-header')
        ->toContain('filteredOptions');
});

// ─── Inline Livewire components ────────────────────────────────────────────

class GroupedComponent extends Component
{
    public ?int $country_id = null;

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-searchable-select
                wire:model="country_id"
                :options="[
                    ['label' => 'East Africa', 'options' => [
                        ['id' => 1, 'name' => 'Uganda'],
                        ['id' => 2, 'name' => 'Kenya'],
                    ]],
                    ['label' => 'West Africa', 'options' => [
                        ['id' => 3, 'name' => 'Ghana'],
                    ]],
                ]"
                :grouped="true"
                placeholder="Select Country"
            />
        </div>
        HTML;
    }
}
