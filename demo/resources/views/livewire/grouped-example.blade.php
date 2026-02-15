<div>
    <div class="space-y-6">
        <div>
            <label for="country_grouped" class="block text-sm font-medium text-gray-700 mb-2">Select a Country (Grouped by
                Region)</label>
            <x-searchable-select :options="$groupedCountries" wire-model="country_id" :selected-value="$country_id"
                placeholder="Select a country..." option-value="value" option-label="label" :grouped="true"
                group-label="label" group-options="options" />
        </div>

        @if ($country_id)
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                @php
                    $selected = collect($countries)->firstWhere('value', $country_id);
                @endphp
                <p class="text-sm text-green-800">
                    <strong>Selected:</strong> {{ $selected['label'] ?? 'N/A' }}<br>
                    <strong>Region:</strong> {{ $selected['group'] ?? 'N/A' }}<br>
                    <strong>ID:</strong> {{ $country_id }}
                </p>
            </div>
        @endif
    </div>
</div>
