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

    // Pre-build options array for Alpine.js
    $alpineOptions = [];
    if ($grouped) {
        foreach ($options as $group) {
            $groupLabelText = is_array($group) ? $group[$groupLabel] : $group->$groupLabel;
            $groupItems = is_array($group) ? $group[$groupOptions] : $group->$groupOptions;
            $items = [];
            foreach ($groupItems as $option) {
                $items[] = [
                    'value' => is_array($option) ? $option[$optionValue] : $option->$optionValue,
                    'label' => is_array($option) ? $option[$optionLabel] : $option->$optionLabel,
                ];
            }
            $alpineOptions[] = [
                'group' => $groupLabelText,
                'items' => $items,
            ];
        }
    } else {
        foreach ($options as $option) {
            $alpineOptions[] = [
                'value' => is_array($option) ? $option[$optionValue] : $option->$optionValue,
                'label' => is_array($option) ? $option[$optionLabel] : $option->$optionLabel,
            ];
        }
    }

    // Build labels map for Alpine
    $labelsMap = [];
    if ($grouped) {
        foreach ($alpineOptions as $group) {
            foreach ($group['items'] as $item) {
                $labelsMap[$item['value']] = $item['label'];
            }
        }
    } else {
        foreach ($alpineOptions as $item) {
            $labelsMap[$item['value']] = $item['label'];
        }
    }
@endphp

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<div x-data="{
    open: false,
    search: '',
    loading: false,
    highlightedIndex: -1,
    multiple: {{ $multiple ? 'true' : 'false' }},
    clearable: {{ $clearable ? 'true' : 'false' }},
    disabled: {{ $disabled ? 'true' : 'false' }},
    options: {{ json_encode($alpineOptions) }},
    selectedValues: {{ json_encode(array_map(fn($v) => is_numeric($v) ? (int) $v : $v, $selectedValues)) }},
    labelsMap: {{ json_encode((object) $labelsMap) }},

    get flatOptions() {
        @if ($grouped) return this.filteredOptions.flatMap(g => g.items);
        @else
            return this.filteredOptions; @endif
    },

    get filteredOptions() {
        if (!this.search) return this.options;
        const query = this.search.toLowerCase();
        @if ($grouped) return this.options.map(group => ({
                ...group,
                items: group.items.filter(item => item.label.toLowerCase().includes(query))
            })).filter(group => group.items.length > 0);
        @else
            return this.options.filter(opt => opt.label.toLowerCase().includes(query)); @endif
    },

    getLabel(value) {
        return this.labelsMap[value] || value;
    },

    isSelected(value) {
        return this.selectedValues.includes(value);
    },

    openDropdown() {
        if (this.disabled) return;
        this.open = true;
        this.highlightedIndex = -1;
        this.$nextTick(() => {
            if (this.$refs.searchInput) this.$refs.searchInput.focus();
        });
    },

    closeDropdown() {
        this.open = false;
        this.search = '';
        this.highlightedIndex = -1;
    },

    toggleDropdown() {
        this.open ? this.closeDropdown() : this.openDropdown();
    },

    handleKeydown(e) {
        if (!this.open) {
            if (['Enter', ' ', 'ArrowDown'].includes(e.key)) {
                e.preventDefault();
                this.openDropdown();
            }
            return;
        }
        const opts = this.flatOptions;
        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                this.highlightedIndex = (this.highlightedIndex + 1) % opts.length;
                this.scrollToHighlighted();
                break;
            case 'ArrowUp':
                e.preventDefault();
                this.highlightedIndex = this.highlightedIndex <= 0 ? opts.length - 1 : this.highlightedIndex - 1;
                this.scrollToHighlighted();
                break;
            case 'Enter':
                e.preventDefault();
                if (this.highlightedIndex >= 0 && this.highlightedIndex < opts.length) {
                    this.toggleSelection(opts[this.highlightedIndex].value);
                }
                break;
            case 'Escape':
                e.preventDefault();
                this.closeDropdown();
                break;
        }
    },

    scrollToHighlighted() {
        this.$nextTick(() => {
            const el = this.$refs.optionsList?.querySelector('[data-highlighted=true]');
            if (el) el.scrollIntoView({ block: 'nearest' });
        });
    },

    toggleSelection(value) {
        if (this.multiple) {
            const index = this.selectedValues.indexOf(value);
            if (index > -1) {
                this.selectedValues.splice(index, 1);
            } else {
                this.selectedValues.push(value);
            }
            $wire.set('{{ $wireModel }}', [...this.selectedValues]);
        } else {
            this.selectedValues = [value];
            $wire.set('{{ $wireModel }}', value);
            this.closeDropdown();
        }
    },

    removeSelection(value) {
        const index = this.selectedValues.indexOf(value);
        if (index > -1) {
            this.selectedValues.splice(index, 1);
            $wire.set('{{ $wireModel }}', this.multiple ? [...this.selectedValues] : null);
        }
    },

    clearAll() {
        this.selectedValues = [];
        $wire.set('{{ $wireModel }}', this.multiple ? [] : null);
        this.search = '';
    },

    async searchApi() {
        if (!{{ $apiUrl ? 'true' : 'false' }}) return;
        this.loading = true;
        try {
            const url = new URL('{{ $apiUrl }}', window.location.origin);
            url.searchParams.set('{{ $apiSearchParam }}', this.search);
            const response = await fetch(url);
            const data = await response.json();
            const items = (data.data || data).map(item => ({
                value: item.{{ $optionValue }},
                label: item.{{ $optionLabel }}
            }));
            this.options = items;
            items.forEach(item => this.labelsMap[item.value] = item.label);
        } catch (error) {
            console.error('Search failed:', error);
        } finally {
            this.loading = false;
        }
    }
}" @click.away="closeDropdown()" @keydown="handleKeydown" class="relative" wire:ignore.self>

    {{-- Trigger --}}
    <div @click="toggleDropdown()"
        {{ $attributes->merge(['class' => 'w-full text-left border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-zinc-800 text-gray-900 dark:text-white cursor-pointer select-none transition-shadow']) }}
        :class="{
            'opacity-50 cursor-not-allowed': disabled,
            'ring-2 ring-blue-500 border-blue-500': open
        }"
        role="combobox" aria-haspopup="listbox" :aria-expanded="open" tabindex="0">
        <div class="flex items-center gap-2 px-3 py-2 min-h-[42px]">
            {{-- Selected display --}}
            <div class="flex-1 min-w-0">
                {{-- Multi-select tags --}}
                <template x-if="multiple && selectedValues.length > 0">
                    <div class="flex flex-wrap gap-1.5">
                        <template x-for="val in selectedValues" :key="val">
                            <span
                                class="inline-flex items-center gap-1 pl-2 pr-1 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 max-w-full">
                                <span class="truncate" x-text="getLabel(val)"></span>
                                <span @click.stop="removeSelection(val)"
                                    class="flex-shrink-0 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-blue-200 dark:hover:bg-blue-800 cursor-pointer transition-colors"
                                    role="button" aria-label="Remove">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12">
                                        </path>
                                    </svg>
                                </span>
                            </span>
                        </template>
                    </div>
                </template>

                {{-- Single select display --}}
                <template x-if="!multiple && selectedValues.length > 0">
                    <span class="block truncate" x-text="getLabel(selectedValues[0])"></span>
                </template>

                {{-- Placeholder --}}
                <template x-if="selectedValues.length === 0">
                    <span class="block truncate text-gray-400 dark:text-gray-500">{{ $placeholder }}</span>
                </template>
            </div>

            {{-- Right side controls --}}
            <div class="flex items-center gap-1 flex-shrink-0">
                {{-- Clear button --}}
                <span x-show="clearable && selectedValues.length > 0 && !disabled" x-cloak @click.stop="clearAll()"
                    class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer transition-colors"
                    role="button" aria-label="Clear selection" title="Clear">
                    <svg class="w-4 h-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </span>

                {{-- Dropdown arrow --}}
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0 transition-transform duration-200"
                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- Dropdown panel --}}
    <div x-show="open && !disabled" x-cloak x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 w-full mt-1 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg overflow-hidden"
        role="listbox" :aria-multiselectable="multiple">

        {{-- Search input --}}
        <input type="text" x-ref="searchInput" x-model="search" @input.debounce.300ms="searchApi()" @click.stop
            placeholder="{{ $searchPlaceholder }}"
            class="w-full px-3 py-2.5 border-b border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none text-sm"
            aria-label="Search options">

        {{-- Options list --}}
        <div class="max-h-60 overflow-auto overscroll-contain" x-ref="optionsList">

            {{-- Loading spinner --}}
            <div x-show="loading" class="px-3 py-3 text-center text-gray-500">
                <svg class="inline w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </div>

            @if ($grouped)
                <template x-for="group in filteredOptions" :key="group.group">
                    <div>
                        <div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-zinc-900 uppercase tracking-wider"
                            x-text="group.group"></div>
                        <template x-for="option in group.items" :key="option.value">
                            <div @click="toggleSelection(option.value)"
                                class="px-3 py-2.5 cursor-pointer flex items-center justify-between transition-colors"
                                :class="{
                                    'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300': isSelected(option
                                        .value),
                                    'hover:bg-gray-100 dark:hover:bg-gray-700': !isSelected(option.value),
                                    'bg-gray-100 dark:bg-gray-700': flatOptions[highlightedIndex]?.value === option
                                        .value
                                }"
                                :data-highlighted="flatOptions[highlightedIndex]?.value === option.value" role="option"
                                :aria-selected="isSelected(option.value)">
                                <span x-text="option.label" class="truncate"></span>
                                <svg x-show="isSelected(option.value)"
                                    class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0 ml-2"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </template>
                    </div>
                </template>
                <div x-show="filteredOptions.length === 0 && !loading"
                    class="px-3 py-3 text-gray-500 dark:text-gray-400 text-sm text-center">
                    {{ $emptyMessage }}
                </div>
            @else
                <template x-for="(option, index) in filteredOptions" :key="option.value">
                    <div @click="toggleSelection(option.value)"
                        class="px-3 py-2.5 cursor-pointer flex items-center justify-between transition-colors"
                        :class="{
                            'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300': isSelected(option.value),
                            'hover:bg-gray-100 dark:hover:bg-gray-700': !isSelected(option.value),
                            'bg-gray-100 dark:bg-gray-700': highlightedIndex === index
                        }"
                        :data-highlighted="highlightedIndex === index" role="option"
                        :aria-selected="isSelected(option.value)">
                        <span x-text="option.label" class="truncate"></span>
                        <svg x-show="isSelected(option.value)"
                            class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0 ml-2" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </template>
                <div x-show="filteredOptions.length === 0 && !loading"
                    class="px-3 py-3 text-gray-500 dark:text-gray-400 text-sm text-center">
                    {{ $emptyMessage }}
                </div>
            @endif
        </div>
    </div>
</div>
