<div>
    <div class="mb-4">
        <h4>Single Select (Bootstrap Theme)</h4>
        <p class="text-muted">Select a country using Bootstrap styling</p>

        <x-searchable-select theme="bootstrap" :options="$countries" wire-model="country_id" :selected-value="$country_id"
            placeholder="Select a country" search-placeholder="Search countries..." />

        @if ($country_id)
            <div class="alert alert-success mt-3">
                Selected Country ID: {{ $country_id }}
            </div>
        @endif
    </div>

    <hr class="my-4">

    <div class="mb-4">
        <h4>Multi-Select (Bootstrap Theme)</h4>
        <p class="text-muted">Select multiple cities with Bootstrap badges</p>

        <x-searchable-select theme="bootstrap" :options="[
            ['id' => 10, 'name' => 'New York'],
            ['id' => 11, 'name' => 'Los Angeles'],
            ['id' => 12, 'name' => 'Chicago'],
            ['id' => 13, 'name' => 'Houston'],
            ['id' => 14, 'name' => 'Phoenix'],
        ]" wire-model="selected_cities" :selected-value="$selected_cities"
            :multiple="true" placeholder="Select cities" search-placeholder="Search cities..." />

        @if (count($selected_cities) > 0)
            <div class="alert alert-info mt-3">
                Selected {{ count($selected_cities) }} cities: {{ implode(', ', $selected_cities) }}
            </div>
        @endif
    </div>
</div>
