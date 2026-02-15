<div>
    <div class="space-y-6">
        <div class="p-6 bg-gradient-to-r from-orange-50 to-yellow-50 border border-orange-200 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-orange-800">API Integration Demo</h3>
                    <div class="mt-2 text-sm text-orange-700">
                        <p class="mb-2">The Searchable Select component supports loading data from API endpoints
                            dynamically.</p>
                        <p class="mb-2"><strong>Features:</strong></p>
                        <ul class="list-disc list-inside space-y-1 ml-4">
                            <li>Debounced search queries to reduce API calls</li>
                            <li>Loading states with spinners</li>
                            <li>Works with any REST API endpoint</li>
                            <li>Perfect for large datasets</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <label for="country_api" class="block text-sm font-medium text-gray-700 mb-2">Search Countries (API
                Powered)</label>
            <x-searchable-select :options="[]" wire-model="country_id" :selected-value="$country_id" api-url="/api/countries"
                api-search-param="search" placeholder="Start typing to search countries..." option-value="value"
                option-label="label" search-placeholder="Type to search via API..." />
        </div>

        @if ($country_id)
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800">
                    <strong>Selected Country ID:</strong> {{ $country_id }}
                </p>
                <p class="text-xs text-gray-600 mt-2">
                    <em>Try typing "united", "can", or "jap" to see the API search in action!</em>
                </p>
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-gray-900 mb-3">How It Works</h4>
            <div class="space-y-4 text-sm text-gray-700">
                <div>
                    <p class="font-medium">Component Usage:</p>
                    <pre class="bg-gray-900 text-gray-100 p-4 rounded overflow-x-auto text-xs mt-2"><code>&lt;x-searchable-select
    :options="[]"
    wire-model="country_id"
    api-url="/api/countries"
    api-search-param="search"
    placeholder="Search countries..."
/&gt;</code></pre>
                </div>

                <div>
                    <p class="font-medium">API Endpoint (routes/web.php):</p>
                    <pre class="bg-gray-900 text-gray-100 p-4 rounded overflow-x-auto text-xs mt-2"><code>Route::get('/api/countries', function () {
    $search = request('search', '');
    $countries = Country::query()
        ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
        ->limit(20)
        ->get(['id as value', 'name as label']);

    return response()->json($countries);
});</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>
