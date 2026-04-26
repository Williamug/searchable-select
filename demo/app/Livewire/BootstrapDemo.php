<?php

namespace App\Livewire;

use Livewire\Component;

class BootstrapDemo extends Component
{
    public $country_id;

    public $selected_countries = [];

    public $region_id;

    public $api_country_id;

    public $countries;

    public $groupedRegions;

    public function mount()
    {
        $this->countries = [
            ['id' => 1, 'name' => 'United States'],
            ['id' => 2, 'name' => 'Canada'],
            ['id' => 3, 'name' => 'United Kingdom'],
            ['id' => 4, 'name' => 'Australia'],
            ['id' => 5, 'name' => 'Germany'],
            ['id' => 6, 'name' => 'France'],
            ['id' => 7, 'name' => 'Italy'],
            ['id' => 8, 'name' => 'Spain'],
            ['id' => 9, 'name' => 'Japan'],
            ['id' => 10, 'name' => 'Brazil'],
            ['id' => 11, 'name' => 'India'],
            ['id' => 12, 'name' => 'South Africa'],
        ];

        $this->groupedRegions = [
            [
                'label' => 'Americas',
                'options' => [
                    ['id' => 1, 'name' => 'United States'],
                    ['id' => 2, 'name' => 'Canada'],
                    ['id' => 3, 'name' => 'Brazil'],
                    ['id' => 4, 'name' => 'Mexico'],
                ],
            ],
            [
                'label' => 'Europe',
                'options' => [
                    ['id' => 5, 'name' => 'United Kingdom'],
                    ['id' => 6, 'name' => 'Germany'],
                    ['id' => 7, 'name' => 'France'],
                    ['id' => 8, 'name' => 'Italy'],
                    ['id' => 9, 'name' => 'Spain'],
                ],
            ],
            [
                'label' => 'Asia Pacific',
                'options' => [
                    ['id' => 10, 'name' => 'Japan'],
                    ['id' => 11, 'name' => 'Australia'],
                    ['id' => 12, 'name' => 'India'],
                    ['id' => 13, 'name' => 'China'],
                ],
            ],
            [
                'label' => 'Africa',
                'options' => [
                    ['id' => 14, 'name' => 'South Africa'],
                    ['id' => 15, 'name' => 'Nigeria'],
                    ['id' => 16, 'name' => 'Kenya'],
                ],
            ],
        ];
    }

    public function render()
    {
        return view('livewire.bootstrap-demo');
    }
}
