<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class DemoPage extends Component
{
    public ?int $country_id = null;

    public array $country_ids = [];

    public ?int $grouped_country_id = null;

    public ?int $preselected_id = 3;

    public bool $some_checkbox = false;

    public ?int $regression_country_id = null;

    // New feature demos
    public ?int $no_search_id = null;

    public array $max_tags_ids = [];

    public ?int $placement_id = null;

    public ?int $async_id = null;

    public array $async_options = [];

    public array $countries = [
        ['id' => 1,  'name' => 'Uganda',       'region' => 'East Africa'],
        ['id' => 2,  'name' => 'Kenya',         'region' => 'East Africa'],
        ['id' => 3,  'name' => 'Tanzania',      'region' => 'East Africa'],
        ['id' => 4,  'name' => 'Ghana',         'region' => 'West Africa'],
        ['id' => 5,  'name' => 'Nigeria',       'region' => 'West Africa'],
        ['id' => 6,  'name' => 'South Africa',  'region' => 'Southern Africa'],
        ['id' => 7,  'name' => 'Ethiopia',      'region' => 'East Africa'],
        ['id' => 8,  'name' => 'Morocco',       'region' => 'North Africa'],
        ['id' => 9,  'name' => 'Egypt',         'region' => 'North Africa'],
        ['id' => 10, 'name' => 'Senegal',       'region' => 'West Africa'],
    ];

    public array $groupedCountries = [
        [
            'label' => 'East Africa',
            'options' => [
                ['id' => 1, 'name' => 'Uganda'],
                ['id' => 2, 'name' => 'Kenya'],
                ['id' => 3, 'name' => 'Tanzania'],
                ['id' => 7, 'name' => 'Ethiopia'],
            ],
        ],
        [
            'label' => 'West Africa',
            'options' => [
                ['id' => 4, 'name' => 'Ghana'],
                ['id' => 5, 'name' => 'Nigeria'],
                ['id' => 10, 'name' => 'Senegal'],
            ],
        ],
        [
            'label' => 'Southern Africa',
            'options' => [
                ['id' => 6, 'name' => 'South Africa'],
            ],
        ],
        [
            'label' => 'North Africa',
            'options' => [
                ['id' => 8, 'name' => 'Morocco'],
                ['id' => 9, 'name' => 'Egypt'],
            ],
        ],
    ];

    public function mount(): void
    {
        $this->async_options = $this->countries;
    }

    #[On('searchable-select:search')]
    public function handleAsyncSearch(string $query, ?string $key): void
    {
        if ($key !== 'async_id') {
            return;
        }

        $this->async_options = array_values(array_filter(
            $this->countries,
            fn ($c) => str_contains(strtolower($c['name']), strtolower($query))
        ));
    }

    public function render()
    {
        return view('livewire.demo-page')
            ->layout('components.layouts.app');
    }
}
