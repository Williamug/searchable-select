<div>
    <div class="space-y-6">
        <div>
            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Select a Country</label>
            <x-searchable-select :options="$countries" wire-model="country_id" :selected-value="$country_id"
                placeholder="Select a country..." option-value="value" option-label="label" />
        </div>

        @if ($country_id)
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800">
                    <strong>Selected:</strong>
                    {{ collect($countries)->firstWhere('value', $country_id)['label'] ?? 'N/A' }}
                    (ID: {{ $country_id }})
                </p>
            </div>
        @endif
    </div>
</div>
