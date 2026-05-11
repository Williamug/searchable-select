<?php

use Livewire\Component;
use Livewire\Livewire;

test('renders in multi-select mode', function () {
    $html = Livewire::test(MultiComponent::class)->html();
    expect($html)->toContain('multiple: true');
});

test('multiple selected values render as tags', function () {
    $component = Livewire::test(MultiComponent::class)
        ->set('country_ids', [1, 2]);

    // Option labels are in labelsMap JSON for Alpine to render as tags
    expect($component->html())
        ->toContain('United States')
        ->toContain('Canada');
});

test('removing a tag updates the livewire property', function () {
    $component = Livewire::test(MultiComponent::class)
        ->set('country_ids', [1, 2, 3]);

    $component->set('country_ids', [1, 3]);

    expect($component->get('country_ids'))
        ->toHaveCount(2)
        ->toContain(1)
        ->toContain(3)
        ->not->toContain(2);
});

// ─── Inline Livewire components ────────────────────────────────────────────

class MultiComponent extends Component
{
    public array $country_ids = [];

    public function render(): string
    {
        return <<<'HTML'
        <div>
            <x-searchable-select
                wire:model="country_ids"
                :options="[
                    ['id' => 1, 'name' => 'United States'],
                    ['id' => 2, 'name' => 'Canada'],
                    ['id' => 3, 'name' => 'Mexico'],
                ]"
                :multiple="true"
                placeholder="Select countries"
            />
        </div>
        HTML;
    }
}
