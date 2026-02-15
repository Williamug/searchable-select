<div>
    <div class="space-y-6">
        <div>
            <label for="countries" class="block text-sm font-medium text-gray-700 mb-2">Select Multiple Countries</label>
            <x-searchable-select :options="$countries" wire-model="country_ids" :selected-value="$country_ids"
                placeholder="Select countries..." option-value="value" option-label="label" :multiple="true" />
        </div>

        @if (!empty($country_ids))
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800 mb-2"><strong>Selected {{ count($country_ids) }} Countries:</strong></p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($country_ids as $id)
                        @php
                            $country = collect($countries)->firstWhere('value', $id);
                        @endphp
                        @if ($country)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $country['label'] }}
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
