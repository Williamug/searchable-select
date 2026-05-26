<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'Uganda', 'code' => 'UG'],
            ['name' => 'Kenya', 'code' => 'KE'],
            ['name' => 'Tanzania', 'code' => 'TZ'],
            ['name' => 'Rwanda', 'code' => 'RW'],
            ['name' => 'Burundi', 'code' => 'BI'],
            ['name' => 'South Sudan', 'code' => 'SS'],
            ['name' => 'Nigeria', 'code' => 'NG'],
            ['name' => 'Ghana', 'code' => 'GH'],
            ['name' => 'South Africa', 'code' => 'ZA'],
            ['name' => 'Egypt', 'code' => 'EG'],
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}
