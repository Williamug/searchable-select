@props([
    'options' => [],
    'wireModel' => '',
    'placeholder' => 'Select option',
    'searchPlaceholder' => 'Search...',
    'disabled' => false,
    'emptyMessage' => 'No options available',
    'selectedValue' => null,
    'optionValue' => 'id',
    'optionLabel' => 'name',
    'multiple' => false,
    'clearable' => true,
    'apiUrl' => null,
    'apiSearchParam' => 'search',
    'grouped' => false,
    'groupLabel' => 'label',
    'groupOptions' => 'options',
])

@php
    $selectedValues = $multiple && is_array($selectedValue) ? $selectedValue : ($selectedValue ? [$selectedValue] : []);
    $hasSelection = !empty($selectedValues);
@endphp

<div x-data="{
    open: false,
    search: '',
    loading: false,
    apiOptions: [],
    async searchApi() {
        if (!{{ $apiUrl ? 'true' : 'false' }}) return;

        this.loading = true;
        try {
            const url = new URL('{{ $apiUrl }}');
            url.searchParams.set('{{ $apiSearchParam }}', this.search);
            const response = await fetch(url);
            const data = await response.json();
            this.apiOptions = data.data || data;
        } catch (error) {
            console.error('Search failed:', error);
        } finally {
            this.loading = false;
        }
    }
}" @click.away="open = false" class="relative">
    <button type="button" @click="open = !open" {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'w-full px-3 py-2 text-left border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed']) }}>
        <span class="block truncate pr-8">
            @if ($multiple && $hasSelection)
                <span class="flex flex-wrap gap-1">
                    @foreach ($selectedValues as $val)
                        @php
                            $item = collect($options)->firstWhere($optionValue, $val);
                            $itemLabel = $item ? (is_array($item) ? $item[$optionLabel] : $item->$optionLabel) : $val;
                        @endphp
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ $itemLabel }}
                            <button type="button"
                                wire:click.stop="$set('{{ $wireModel }}', {{ json_encode(array_values(array_diff($selectedValues, [$val]))) }})"
                                class="ml-1 inline-flex items-center p-0.5 text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-100">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>
                    @endforeach
                </span>
            @elseif ($hasSelection)
                @php
                    $item = collect($options)->firstWhere($optionValue, $selectedValues[0]);
                    $displayLabel = $item
                        ? (is_array($item)
                            ? $item[$optionLabel]
                            : $item->$optionLabel)
                        : $selectedValues[0];
                @endphp
                {{ $displayLabel }}
            @else
                {{ $placeholder }}
            @endif
        </span>

        <span class="absolute inset-y-0 right-0 flex items-center pr-2">
            @if ($clearable && $hasSelection && !$disabled)
                <button type="button" wire:click.stop="$set('{{ $wireModel }}', {{ $multiple ? '[]' : 'null' }})"
                    class="p-1 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-full mr-1">
                    <svg class="w-4 h-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            @endif
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </span>
    </button>

    <div x-show="open && !{{ $disabled ? 'true' : 'false' }}" x-cloak
        class="absolute z-10 w-full mt-1 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-hidden">
        <input type="text" x-model="search" @input.debounce.300ms="searchApi()" @click.stop
            placeholder="{{ $searchPlaceholder }}"
            class="w-full px-3 py-2 border-b border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none">

        <div class="max-h-48 overflow-auto">
            <div x-show="loading" class="px-3 py-2 text-center text-gray-500">
                <svg class="inline w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </div>

            @if ($grouped)
                @forelse ($options as $group)
                    @php
                        $groupLabelText = is_array($group) ? $group[$groupLabel] : $group->$groupLabel;
                        $groupItems = is_array($group) ? $group[$groupOptions] : $group->$groupOptions;
                    @endphp
                    <div>
                        <div
                            class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900">
                            {{ $groupLabelText }}
                        </div>
                        @foreach ($groupItems as $option)
                            @php
                                $value = is_array($option) ? $option[$optionValue] : $option->$optionValue;
                                $label = is_array($option) ? $option[$optionLabel] : $option->$optionLabel;
                                $isSelected = in_array($value, $selectedValues);
                            @endphp
                            <div x-show="search === '' || '{{ strtolower($label) }}'.includes(search.toLowerCase())"
                                @click="
                                    @if ($multiple) const current = {{ json_encode($selectedValues) }};
                                        const index = current.indexOf({{ $value }});
                                        if (index > -1) {
                                            current.splice(index, 1);
                                        } else {
                                            current.push({{ $value }});
                                        }
                                        $wire.set('{{ $wireModel }}', current);
                                    @else
                                        $wire.set('{{ $wireModel }}', {{ $value }});
                                        open = false;
                                        search = ''; @endif
                                "
                                class="px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center justify-between {{ $isSelected ? 'bg-blue-100 dark:bg-blue-900' : '' }}">
                                <span>{{ $label }}</span>
                                @if ($multiple && $isSelected)
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @empty
                    <div class="px-3 py-2 text-gray-500 dark:text-gray-400">
                        {{ $emptyMessage }}
                    </div>
                @endforelse
            @else
                @forelse ($options as $option)
                    @php
                        $value = is_array($option) ? $option[$optionValue] : $option->$optionValue;
                        $label = is_array($option) ? $option[$optionLabel] : $option->$optionLabel;
                        $isSelected = in_array($value, $selectedValues);
                    @endphp
                    <div x-show="search === '' || '{{ strtolower($label) }}'.includes(search.toLowerCase())"
                        @click="
                            @if ($multiple) const current = {{ json_encode($selectedValues) }};
                                const index = current.indexOf({{ $value }});
                                if (index > -1) {
                                    current.splice(index, 1);
                                } else {
                                    current.push({{ $value }});
                                }
                                $wire.set('{{ $wireModel }}', current);
                            @else
                                $wire.set('{{ $wireModel }}', {{ $value }});
                                open = false;
                                search = ''; @endif
                        "
                        class="px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center justify-between {{ $isSelected ? 'bg-blue-100 dark:bg-blue-900' : '' }}">
                        <span>{{ $label }}</span>
                        @if ($multiple && $isSelected)
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>
                @empty
                    <div class="px-3 py-2 text-gray-500 dark:text-gray-400">
                        {{ $emptyMessage }}
                    </div>
                @endforelse
            @endif
        </div>
    </div>
</div>
