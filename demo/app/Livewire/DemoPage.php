<?php

namespace App\Livewire;

use Livewire\Component;

class DemoPage extends Component
{
    public ?int $country_id = null;

    public array $country_ids = [];

    public ?int $grouped_country_id = null;

    public ?int $preselected_id = 3;

    public bool $some_checkbox = false;

    public ?int $regression_country_id = null;

    public array $countries = [
        ['id' => 1, 'name' => 'Uganda'],
        ['id' => 2, 'name' => 'Kenya'],
        ['id' => 3, 'name' => 'Tanzania'],
        ['id' => 4, 'name' => 'Ghana'],
        ['id' => 5, 'name' => 'Nigeria'],
        ['id' => 6, 'name' => 'South Africa'],
        ['id' => 7, 'name' => 'Ethiopia'],
        ['id' => 8, 'name' => 'Morocco'],
        ['id' => 9, 'name' => 'Egypt'],
        ['id' => 10, 'name' => 'Senegal'],
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

    public function render()
    {
        return view('livewire.demo-page')
            ->layout('components.layouts.app');
    }
}
