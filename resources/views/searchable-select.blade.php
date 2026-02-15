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
])

<div x-data="{ open: false, search: '' }" @click.away="open = false" class="relative">
    <button type="button" @click="open = !open" {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'w-full px-3 py-2 text-left border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed']) }}>
        <span class="block truncate">
            @if ($selectedValue)
                {{ collect($options)->firstWhere($optionValue, $selectedValue)[$optionLabel] ?? $placeholder }}
            @else
                {{ $placeholder }}
            @endif
        </span>
        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </span>
    </button>

    <div x-show="open && !{{ $disabled ? 'true' : 'false' }}" x-cloak
        class="absolute z-10 w-full mt-1 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-hidden">
        <input type="text" x-model="search" @click.stop placeholder="{{ $searchPlaceholder }}"
            class="w-full px-3 py-2 border-b border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none">

        <ul class="max-h-48 overflow-auto">
            @forelse ($options as $option)
                @php
                    $value = is_array($option) ? $option[$optionValue] : $option->$optionValue;
                    $label = is_array($option) ? $option[$optionLabel] : $option->$optionLabel;
                @endphp
                <li x-show="search === '' || '{{ strtolower($label) }}'.includes(search.toLowerCase())"
                    wire:click="$set('{{ $wireModel }}', {{ $value }})" @click="open = false; search = ''"
                    class="px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 {{ $selectedValue == $value ? 'bg-blue-100 dark:bg-blue-900' : '' }}">
                    {{ $label }}
                </li>
            @empty
                <li class="px-3 py-2 text-gray-500 dark:text-gray-400">
                    {{ $emptyMessage }}
                </li>
            @endforelse
        </ul>
    </div>
</div>
