<div>
    <x-searchable-select :options="$countries" wire:model="country_id" placeholder="Select a country" />

    @if ($country_id)
        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800">
                <strong>Selected:</strong> {{ collect($countries)->firstWhere('value', $country_id)['label'] ?? 'N/A' }}
                (ID: {{ $country_id }})
            </p>
        </div>
    @endif
</div>
