<div>
    <x-searchable-select wire:model="country_id" placeholder="Search countries..." apiUrl="/api/countries"
        apiSearchParam="search" />

    @if ($country_id)
        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800">
                <strong>Selected Country ID:</strong> {{ $country_id }}
            </p>
            <p class="text-xs text-green-600 mt-2">
                Data loaded from API endpoint: /api/countries
            </p>
        </div>
    @endif
</div>
