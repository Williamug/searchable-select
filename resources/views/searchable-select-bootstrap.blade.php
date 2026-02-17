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

    /* Prevent icon fonts from overriding our symbols */
    .searchable-select-bootstrap * {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
    }
</style>

<div class="searchable-select-bootstrap" x-data="{
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
}" @click.away="closeDropdown()" @keydown="handleKeydown"
    class="position-relative" wire:ignore.self>

    {{-- Trigger --}}
    <div @click="toggleDropdown()"
        {{ $attributes->except(['options', 'wireModel', 'placeholder', 'searchPlaceholder', 'disabled', 'emptyMessage', 'selectedValue', 'optionValue', 'optionLabel', 'multiple', 'clearable', 'apiUrl', 'apiSearchParam', 'grouped', 'groupLabel', 'groupOptions', 'theme'])->merge(['class' => 'form-control']) }}
        :class="{
            'disabled opacity-50': disabled,
            'border-primary shadow-sm': open
        }"
        role="combobox" aria-haspopup="listbox" :aria-expanded="open" tabindex="0"
        style="cursor: pointer; min-height: 42px; user-select: none; display: flex; align-items: center;">

        {{-- Selected display --}}
        <div style="flex: 1; min-width: 0; overflow: hidden;">
            {{-- Multi-select tags --}}
            <template x-if="multiple && selectedValues.length > 0">
                <div class="d-flex flex-wrap gap-1">
                    <template x-for="val in selectedValues" :key="val">
                        <span class="badge bg-primary rounded-pill d-inline-flex align-items-center gap-1 px-2 py-1">
                            <span class="text-truncate" x-text="getLabel(val)" style="max-width: 150px;"></span>
                            <span @click.stop="removeSelection(val)"
                                class="d-inline-flex align-items-center justify-content-center" role="button"
                                aria-label="Remove" style="cursor: pointer; opacity: 0.9;">
                                <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" style="display: block;">
                                    <path d="M1 1L9 9M9 1L1 9" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </span>
                        </span>
                    </template>
                </div>
            </template>

            {{-- Single select display --}}
            <template x-if="!multiple && selectedValues.length > 0">
                <span class="d-block text-truncate" x-text="getLabel(selectedValues[0])"></span>
            </template>

            {{-- Placeholder --}}
            <template x-if="selectedValues.length === 0">
                <span class="d-block text-truncate text-muted">{{ $placeholder }}</span>
            </template>
        </div>

        {{-- Right side controls --}}
        <div class="d-flex align-items-center gap-1 ms-2" style="flex-shrink: 0;">
            {{-- Clear button --}}
            <span x-show="clearable && selectedValues.length > 0 && !disabled" x-cloak @click.stop="clearAll()"
                role="button" aria-label="Clear selection" title="Clear"
                style="cursor: pointer; line-height: 1; color: #6c757d; padding: 0 4px; display: inline-flex; align-items: center;">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"
                    style="display: block;">
                    <path d="M1 1L13 13M13 1L1 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </span>

            {{-- Dropdown arrow --}}
            <span
                style="color: #6c757d; line-height: 1; transition: transform 0.2s; display: inline-flex; align-items: center;"
                :style="{ transform: open ? 'rotate(180deg)' : 'rotate(0deg)' }">
                <svg width="10" height="10" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg"
                    style="display: block;">
                    <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </span>
        </div>
    </div>

    {{-- Dropdown panel --}}
    <div x-show="open && !disabled" x-cloak x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="dropdown-menu show position-absolute shadow border rounded overflow-hidden w-100 mt-1" role="listbox"
        :aria-multiselectable="multiple" style="z-index: 1050; display: block;">

        {{-- Search input --}}
        <input type="text" x-ref="searchInput" x-model="search" @input.debounce.300ms="searchApi()" @click.stop
            placeholder="{{ $searchPlaceholder }}" class="form-control border-0 border-bottom rounded-0"
            aria-label="Search options" style="outline: none; box-shadow: none;">

        {{-- Options list --}}
        <div style="max-height: 240px; overflow-y: auto; overscroll-behavior: contain;" x-ref="optionsList">

            {{-- Loading spinner --}}
            <div x-show="loading" class="text-center py-3 text-muted">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            @if ($grouped)
                <template x-for="group in filteredOptions" :key="group.group">
                    <div>
                        <div class="dropdown-header bg-light text-muted small fw-semibold text-uppercase"
                            x-text="group.group"></div>
                        <template x-for="option in group.items" :key="option.value">
                            <a href="#" @click.prevent="toggleSelection(option.value)"
                                class="dropdown-item d-flex align-items-center justify-content-between py-2"
                                :class="{
                                    'active': isSelected(option.value),
                                    'bg-light': flatOptions[highlightedIndex]?.value === option.value && !isSelected(
                                        option.value)
                                }"
                                :data-highlighted="flatOptions[highlightedIndex]?.value === option.value" role="option"
                                :aria-selected="isSelected(option.value)">
                                <span x-text="option.label" class="text-truncate"></span>
                                <span x-show="isSelected(option.value)" class="ms-2"
                                    style="display: inline-flex; align-items: center;">
                                    <svg width="14" height="14" viewBox="0 0 14 11" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" style="display: block;">
                                        <path d="M1 5.5L5 9.5L13 1.5" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </a>
                        </template>
                    </div>
                </template>
                <div x-show="filteredOptions.length === 0 && !loading"
                    class="dropdown-item-text text-center text-muted py-3">
                    {{ $emptyMessage }}
                </div>
            @else
                <template x-for="(option, index) in filteredOptions" :key="option.value">
                    <a href="#" @click.prevent="toggleSelection(option.value)"
                        class="dropdown-item d-flex align-items-center justify-content-between py-2"
                        :class="{
                            'active': isSelected(option.value),
                            'bg-light': highlightedIndex === index && !isSelected(option.value)
                        }"
                        :data-highlighted="highlightedIndex === index" role="option"
                        :aria-selected="isSelected(option.value)">
                        <span x-text="option.label" class="text-truncate"></span>
                        <span x-show="isSelected(option.value)" class="ms-2"
                            style="display: inline-flex; align-items: center;">
                            <svg width="14" height="14" viewBox="0 0 14 11" fill="none"
                                xmlns="http://www.w3.org/2000/svg" style="display: block;">
                                <path d="M1 5.5L5 9.5L13 1.5" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </a>
                </template>
                <div x-show="filteredOptions.length === 0 && !loading"
                    class="dropdown-item-text text-center text-muted py-3">
                    {{ $emptyMessage }}
                </div>
            @endif
        </div>
    </div>
</div>
