<div class="space-y-8">

    {{-- Page header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Searchable Select</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">
            A Blade + Alpine.js searchable dropdown for Laravel with Livewire. No wire:ignore, no @click.outside, no race conditions.
        </p>
    </div>

    {{-- Example 1: Basic single-select --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">1. Basic single-select</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Country dropdown bound to a Livewire property.</p>

        <div class="max-w-sm">
            <x-searchable-select
                wire:model="country_id"
                :options="$countries"
                placeholder="Select a country"
            />
        </div>

        <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
            Selected ID: <span class="font-mono font-medium text-blue-600 dark:text-blue-400">{{ $country_id ?? 'null' }}</span>
        </div>
    </div>

    {{-- Example 2: Multi-select --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">2. Multi-select with Select all / Clear all</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Tag-style selection with bulk action buttons in the dropdown.</p>

        <div class="max-w-sm">
            <x-searchable-select
                wire:model="country_ids"
                :options="$countries"
                :multiple="true"
                placeholder="Select countries"
            />
        </div>

        <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
            Selected IDs: <span class="font-mono font-medium text-blue-600 dark:text-blue-400">{{ json_encode($country_ids) }}</span>
        </div>
    </div>

    {{-- Example 3: Multi-select with max-tags --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">3. Multi-select with max-tags overflow</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            <code class="font-mono bg-gray-100 dark:bg-zinc-700 px-1 rounded">:max-tags="2"</code> — shows first 2 tags then a "+N more" badge.
        </p>

        <div class="max-w-sm">
            <x-searchable-select
                wire:model="max_tags_ids"
                :options="$countries"
                :multiple="true"
                :max-tags="2"
                placeholder="Select countries"
            />
        </div>

        <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
            Selected IDs: <span class="font-mono font-medium text-blue-600 dark:text-blue-400">{{ json_encode($max_tags_ids) }}</span>
        </div>
    </div>

    {{-- Example 4: Grouped options --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">4. Grouped options</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Continent headers group the country options.</p>

        <div class="max-w-sm">
            <x-searchable-select
                wire:model="grouped_country_id"
                :options="$groupedCountries"
                :grouped="true"
                placeholder="Select a country"
                group-label="label"
                group-options="options"
            />
        </div>

        <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
            Selected ID: <span class="font-mono font-medium text-blue-600 dark:text-blue-400">{{ $grouped_country_id ?? 'null' }}</span>
        </div>
    </div>

    {{-- Example 5: Subtitle + icon --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">5. Custom option subtitle</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            <code class="font-mono bg-gray-100 dark:bg-zinc-700 px-1 rounded">option-subtitle="region"</code> — shows a second line per option.
        </p>

        <div class="max-w-sm">
            <x-searchable-select
                wire:model="country_id"
                :options="$countries"
                option-subtitle="region"
                placeholder="Select a country"
            />
        </div>

        <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
            Selected ID: <span class="font-mono font-medium text-blue-600 dark:text-blue-400">{{ $country_id ?? 'null' }}</span>
        </div>
    </div>

    {{-- Example 6: No search input --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">6. Search disabled</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            <code class="font-mono bg-gray-100 dark:bg-zinc-700 px-1 rounded">:searchable="false"</code> — hides the search input for short option lists.
        </p>

        <div class="max-w-sm">
            <x-searchable-select
                wire:model="no_search_id"
                :options="$countries"
                :searchable="false"
                placeholder="Select a country"
            />
        </div>

        <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
            Selected ID: <span class="font-mono font-medium text-blue-600 dark:text-blue-400">{{ $no_search_id ?? 'null' }}</span>
        </div>
    </div>

    {{-- Example 7: Min-length --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">7. Min-length search</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            <code class="font-mono bg-gray-100 dark:bg-zinc-700 px-1 rounded">:min-length="2"</code> — filtering only starts after 2 characters are typed.
        </p>

        <div class="max-w-sm">
            <x-searchable-select
                wire:model="country_id"
                :options="$countries"
                :min-length="2"
                placeholder="Select a country"
                search-placeholder="Type 2+ chars to filter..."
            />
        </div>

        <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
            Selected ID: <span class="font-mono font-medium text-blue-600 dark:text-blue-400">{{ $country_id ?? 'null' }}</span>
        </div>
    </div>

    {{-- Example 8: Placement --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">8. Forced placement</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            <code class="font-mono bg-gray-100 dark:bg-zinc-700 px-1 rounded">placement="top"</code> — dropdown always opens upward regardless of available space.
        </p>

        <div class="max-w-sm">
            <x-searchable-select
                wire:model="placement_id"
                :options="$countries"
                placement="top"
                placeholder="Always opens upward"
            />
        </div>

        <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
            Selected ID: <span class="font-mono font-medium text-blue-600 dark:text-blue-400">{{ $placement_id ?? 'null' }}</span>
        </div>
    </div>

    {{-- Example 9: Custom max-height --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">9. Custom max-height</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            <code class="font-mono bg-gray-100 dark:bg-zinc-700 px-1 rounded">max-height="120px"</code> — compact dropdown that shows ~3 options at a time.
        </p>

        <div class="max-w-sm">
            <x-searchable-select
                wire:model="country_id"
                :options="$countries"
                max-height="120px"
                placeholder="Select a country"
            />
        </div>

        <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
            Selected ID: <span class="font-mono font-medium text-blue-600 dark:text-blue-400">{{ $country_id ?? 'null' }}</span>
        </div>
    </div>

    {{-- Example 10: Async search --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">10. Async / server-side search</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            <code class="font-mono bg-gray-100 dark:bg-zinc-700 px-1 rounded">:async="true"</code> — dispatches <code class="font-mono bg-gray-100 dark:bg-zinc-700 px-1 rounded">searchable-select:search</code> to the Livewire component on each keystroke. A spinner shows while waiting for results.
        </p>

        <div class="max-w-sm">
            <x-searchable-select
                wire:model="async_id"
                :options="$async_options"
                :async="true"
                placeholder="Type to search server-side"
                search-placeholder="Search countries..."
            />
        </div>

        <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
            Selected ID: <span class="font-mono font-medium text-blue-600 dark:text-blue-400">{{ $async_id ?? 'null' }}</span>
        </div>
    </div>

    {{-- Example 11: Pre-selected value --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">11. Pre-selected value</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Component mounted with <code class="font-mono bg-gray-100 dark:bg-zinc-700 px-1 rounded">preselected_id = 3</code> (Tanzania).</p>

        <div class="max-w-sm">
            <x-searchable-select
                wire:model="preselected_id"
                :options="$countries"
                placeholder="Select a country"
            />
        </div>

        <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
            Selected ID: <span class="font-mono font-medium text-blue-600 dark:text-blue-400">{{ $preselected_id ?? 'null' }}</span>
        </div>
    </div>

    {{-- Example 12: Disabled state --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">12. Disabled state</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">The dropdown cannot be opened when disabled.</p>

        <div class="max-w-sm">
            <x-searchable-select
                wire:model="country_id"
                :options="$countries"
                :disabled="true"
                placeholder="Disabled — cannot open"
            />
        </div>
    </div>

    {{-- Example 13: Regression test --}}
    <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl shadow-sm border-2 border-amber-400 dark:border-amber-600 p-6">
        <div class="flex items-center gap-2 mb-1">
            <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">13. Regression test: checkbox must not open the dropdown</h2>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            Checking the checkbox triggers a Livewire round-trip. The dropdown below must stay closed.
            This reproduces the classic race condition caused by <code class="font-mono bg-gray-100 dark:bg-zinc-700 px-1 rounded">wire:ignore</code> + <code class="font-mono bg-gray-100 dark:bg-zinc-700 px-1 rounded">$wire.$watch()</code>.
        </p>

        <div class="flex items-center gap-3 mb-4 p-3 bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700">
            <input
                type="checkbox"
                id="some_checkbox"
                wire:model.live="some_checkbox"
                class="w-4 h-4 text-blue-600 rounded border-gray-300 cursor-pointer"
            >
            <label for="some_checkbox" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                Toggle me — the dropdown below must NOT open
            </label>
            <span class="ml-auto text-xs font-mono text-gray-500">{{ $some_checkbox ? 'checked ✓' : 'unchecked' }}</span>
        </div>

        <div class="max-w-sm">
            <x-searchable-select
                wire:model="regression_country_id"
                :options="$countries"
                placeholder="This must stay closed when checkbox is toggled"
            />
        </div>

        <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
            Selected ID: <span class="font-mono font-medium text-blue-600 dark:text-blue-400">{{ $regression_country_id ?? 'null' }}</span>
        </div>
    </div>

</div>
