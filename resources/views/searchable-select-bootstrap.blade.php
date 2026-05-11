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

    $labelsMap = [];
    if ($grouped) {
        foreach ($alpineOptions as $group) {
            foreach ($group['items'] as $item) {
                $labelsMap[(string) $item['value']] = $item['label'];
            }
        }
    } else {
        foreach ($alpineOptions as $item) {
            $labelsMap[(string) $item['value']] = $item['label'];
        }
    }

    $mappedSelectedValues = array_map(fn($v) => is_numeric($v) ? (int) $v : $v, $selectedValues);
@endphp

@once
<style>
    .searchable-select-bootstrap * {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
</style>
@endonce

@include('searchable-select::partials._searchable-select-script')

<div class="searchable-select-bootstrap position-relative" x-data="searchableSelect({
    multiple: {{ $multiple ? 'true' : 'false' }},
    clearable: {{ $clearable ? 'true' : 'false' }},
    disabled: {{ $disabled ? 'true' : 'false' }},
    apiUrl: {{ json_encode($apiUrl) }},
    apiSearchParam: {{ json_encode($apiSearchParam) }},
    optionValueKey: {{ json_encode($optionValue) }},
    optionLabelKey: {{ json_encode($optionLabel) }},
    options: @json($alpineOptions),
    selectedValues: @json($mappedSelectedValues),
    labelsMap: @json((object) $labelsMap),
    wireModel: {{ json_encode($wireModel) }},
    grouped: {{ $grouped ? 'true' : 'false' }}
})" @click.outside="closeDropdown()" @keydown="handleKeydown" wire:ignore.self>

    {{-- Trigger --}}
    <div @click="toggleDropdown()"
        {{ $attributes->except(['options', 'wireModel', 'placeholder', 'searchPlaceholder', 'disabled', 'emptyMessage', 'selectedValue', 'optionValue', 'optionLabel', 'multiple', 'clearable', 'apiUrl', 'apiSearchParam', 'grouped', 'groupLabel', 'groupOptions'])->merge(['class' => 'form-control']) }}
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
    <div x-show="open && !disabled" x-cloak x-transition
        class="dropdown-menu show position-absolute shadow border rounded overflow-hidden w-100 mt-1"
        role="listbox" :aria-multiselectable="multiple" style="z-index: 1050;">

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

            {{-- Error message --}}
            <div x-show="error && !loading" class="dropdown-item-text text-center text-danger py-3 small">
                <span x-text="error"></span>
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
                <div x-show="filteredOptions.length === 0 && !loading && !error"
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
                <div x-show="filteredOptions.length === 0 && !loading && !error"
                    class="dropdown-item-text text-center text-muted py-3">
                    {{ $emptyMessage }}
                </div>
            @endif
        </div>
    </div>
</div>
