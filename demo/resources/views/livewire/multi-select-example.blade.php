<div>
    <x-searchable-select :options="$countries" wire:model="country_ids" placeholder="Select countries" multiSelect="true"
        clearButton="true" />

    @if (count($country_ids) > 0)
        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800 mb-2">
                <strong>Selected {{ count($country_ids) }} countries:</strong>
            </p>
            <ul class="list-disc list-inside text-sm text-green-700">
                @foreach ($country_ids as $id)
                    <li>{{ collect($countries)->firstWhere('value', $id)['label'] ?? 'N/A' }} (ID: {{ $id }})
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
