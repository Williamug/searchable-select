@php
    $wireModelKey = $attributes->whereStartsWith('wire:model')->first();

    $alpineOptions = [];
    if ($grouped) {
        foreach ($options as $group) {
            $groupLabelText = is_array($group) ? $group[$groupLabel] : $group->{$groupLabel};
            $groupItems = is_array($group) ? $group[$groupOptions] : $group->{$groupOptions};
            $items = [];
            foreach ($groupItems as $opt) {
                $items[] = [
                    'value' => is_array($opt) ? $opt[$optionValue] : $opt->{$optionValue},
                    'label' => is_array($opt) ? $opt[$optionLabel] : $opt->{$optionLabel},
                ];
            }
            $alpineOptions[] = ['group' => $groupLabelText, 'items' => $items];
        }
    } else {
        foreach ($options as $opt) {
            $alpineOptions[] = [
                'value' => is_array($opt) ? $opt[$optionValue] : $opt->{$optionValue},
                'label' => is_array($opt) ? $opt[$optionLabel] : $opt->{$optionLabel},
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

    $skipAttrs = ['options', 'option-value', 'option-label', 'placeholder', 'search-placeholder',
        'empty-message', 'multiple', 'clearable', 'disabled', 'grouped', 'group-label', 'group-options'];
@endphp

@once
<style>[x-cloak] { display: none !important; }</style>
<script>
(function () {
    if (window.__searchableSelectRegistered) return;

    function register() {
        Alpine.data('searchableSelect', (config) => ({
            isOpen: false,
            search: '',
            highlightedIndex: -1,
            selected: config.multiple ? [] : null,
            options: config.options ?? [],
            labelsMap: config.labelsMap ?? {},
            multiple: config.multiple ?? false,
            clearable: config.clearable ?? true,
            disabled: config.disabled ?? false,
            grouped: config.grouped ?? false,
            wireModelKey: config.wireModelKey ?? null,

            // Dropdown positioning (fixed, so it always clears other elements)
            dropdownX: 0,
            dropdownY: 0,
            dropdownBottom: 0,
            dropdownWidth: 0,
            dropdownOpenUpward: false,

            _onNavigating: null,
            _onNavigated: null,
            _onScroll: null,
            _onResize: null,
            _refreshOptions: null,
            _syncing: false,

            init() {
                if (this.wireModelKey && this.$wire) {
                    try {
                        // Alpine.raw() unwraps Livewire 4 reactive proxies back to plain values.
                        // The object guard rejects any proxy that wasn't fully unwrapped (e.g.
                        // the entire $wire proxy being returned instead of the property value).
                        const safeRaw = (v) => {
                            const r = (window.Alpine?.raw) ? Alpine.raw(v) : v;
                            return (r !== null && r !== undefined && typeof r === 'object' && !Array.isArray(r))
                                ? (this.multiple ? [] : null)
                                : r;
                        };

                        this.selected = safeRaw(this.$wire.get(this.wireModelKey)) ?? (this.multiple ? [] : null);

                        this.$watch('selected', (value) => {
                            if (!this._syncing) {
                                this.$wire.set(this.wireModelKey, value);
                            }
                        });

                        this.$wire.$watch(this.wireModelKey, (value) => {
                            const normalized = safeRaw(value) ?? (this.multiple ? [] : null);
                            if (JSON.stringify(normalized) !== JSON.stringify(this.selected)) {
                                this._syncing = true;
                                this.selected = normalized;
                                this.$nextTick(() => { this._syncing = false; });
                            }
                        });
                    } catch (e) {}
                }

                this._onNavigating = () => this.close();
                this._onNavigated = () => { this.search = ''; };

                // Reposition while open so the dropdown tracks the trigger on scroll/resize
                this._onScroll = () => { if (this.isOpen) this.updatePosition(); };
                this._onResize = () => { if (this.isOpen) this.updatePosition(); };

                // Livewire re-renders update the DOM but don't re-run x-data. Read fresh
                // options from data attributes that Livewire's morphdom DOES update.
                this._refreshOptions = () => {
                    try {
                        const opts = this.$el.getAttribute('data-searchable-options');
                        const map  = this.$el.getAttribute('data-searchable-labels');
                        if (opts !== null) this.options    = JSON.parse(opts);
                        if (map  !== null) this.labelsMap  = JSON.parse(map);
                    } catch (e) {}
                };

                // Always read options from data attributes on init. This is more reliable
                // than x-data JSON in Livewire 4, where the attribute is re-evaluated
                // after hydration and entity-encoded JSON can parse incorrectly.
                this._refreshOptions();

                document.addEventListener('livewire:navigating', this._onNavigating);
                document.addEventListener('livewire:navigated',  this._onNavigated);
                document.addEventListener('livewire:morphed',    this._refreshOptions);
                window.addEventListener('scroll', this._onScroll, { passive: true, capture: true });
                window.addEventListener('resize', this._onResize, { passive: true });
            },

            destroy() {
                document.removeEventListener('livewire:navigating', this._onNavigating);
                document.removeEventListener('livewire:navigated',  this._onNavigated);
                document.removeEventListener('livewire:morphed',    this._refreshOptions);
                window.removeEventListener('scroll', this._onScroll, true);
                window.removeEventListener('resize', this._onResize);
            },

            // Compute fixed-position coordinates from the trigger's bounding rect.
            // position:fixed uses viewport coords directly — no scroll offset needed.
            updatePosition() {
                const trigger = this.$refs.trigger;
                if (!trigger) return;
                const rect = trigger.getBoundingClientRect();
                const spaceBelow = window.innerHeight - rect.bottom;
                const maxH = 304; // matches max-h-76 below (~19 rem)
                this.dropdownOpenUpward = spaceBelow < maxH && rect.top > spaceBelow;
                this.dropdownX      = rect.left;
                this.dropdownY      = rect.bottom + 4;
                this.dropdownBottom = window.innerHeight - rect.top + 4;
                this.dropdownWidth  = rect.width;
            },

            get flatOptions() {
                if (this.grouped) {
                    return this.filteredOptions.flatMap(g => g.items);
                }
                return this.filteredOptions;
            },

            get filteredOptions() {
                if (!this.search.trim()) return this.options;
                const q = this.search.toLowerCase();
                if (this.grouped) {
                    return this.options
                        .map(g => ({ ...g, items: g.items.filter(i => i.label.toLowerCase().includes(q)) }))
                        .filter(g => g.items.length > 0);
                }
                return this.options.filter(o => o.label.toLowerCase().includes(q));
            },

            getLabel(value) {
                return this.labelsMap[String(value)] ?? String(value);
            },

            isSelected(value) {
                if (this.multiple) {
                    return Array.isArray(this.selected) &&
                        this.selected.some(v => String(v) === String(value));
                }
                return this.selected !== null && String(this.selected) === String(value);
            },

            open() {
                if (this.disabled || this.isOpen) return;
                this.updatePosition();
                this.isOpen = true;
                this.highlightedIndex = -1;
                this.$nextTick(() => this.$refs.searchInput?.focus());
            },

            close() {
                if (!this.isOpen) return;
                this.isOpen = false;
                this.search = '';
                this.highlightedIndex = -1;
            },

            toggle() {
                this.isOpen ? this.close() : this.open();
            },

            select(value) {
                if (this.multiple) {
                    if (!Array.isArray(this.selected)) this.selected = [];
                    const idx = this.selected.findIndex(v => String(v) === String(value));
                    if (idx > -1) {
                        this.selected = this.selected.filter((_, i) => i !== idx);
                    } else {
                        this.selected = [...this.selected, value];
                    }
                } else {
                    this.selected = value;
                    this.close();
                }
            },

            removeTag(value) {
                if (!Array.isArray(this.selected)) return;
                this.selected = this.selected.filter(v => String(v) !== String(value));
            },

            clearAll() {
                this.selected = this.multiple ? [] : null;
                this.search = '';
            },

            handleKeydown(e) {
                if (!this.isOpen) {
                    if (['Enter', ' ', 'ArrowDown'].includes(e.key)) {
                        e.preventDefault();
                        this.open();
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
                        this.highlightedIndex = this.highlightedIndex <= 0
                            ? opts.length - 1 : this.highlightedIndex - 1;
                        this.scrollToHighlighted();
                        break;
                    case 'Enter':
                        e.preventDefault();
                        if (this.highlightedIndex >= 0 && opts[this.highlightedIndex]) {
                            this.select(opts[this.highlightedIndex].value);
                        }
                        break;
                    case 'Escape':
                    case 'Tab':
                        e.preventDefault();
                        this.close();
                        break;
                }
            },

            scrollToHighlighted() {
                this.$nextTick(() => {
                    this.$refs.optionsList
                        ?.querySelector('[data-highlighted="true"]')
                        ?.scrollIntoView({ block: 'nearest' });
                });
            },
        }));

        window.__searchableSelectRegistered = true;
    }

    if (window.Alpine) {
        register();
    } else {
        document.addEventListener('alpine:init', register, { once: true });
    }

    document.addEventListener('livewire:init', () => {
        if (window.Alpine && !window.__searchableSelectRegistered) {
            register();
        }
    });
})();
</script>
@endonce

<div
    x-data="searchableSelect({
        multiple: {{ $multiple ? 'true' : 'false' }},
        clearable: {{ $clearable ? 'true' : 'false' }},
        disabled: {{ $disabled ? 'true' : 'false' }},
        grouped: {{ $grouped ? 'true' : 'false' }},
        wireModelKey: {{ json_encode($wireModelKey) }},
        options: {{ json_encode($alpineOptions) }},
        labelsMap: {{ json_encode((object) $labelsMap) }},
    })"
    @keydown="handleKeydown"
    data-searchable-options="{{ json_encode($alpineOptions) }}"
    data-searchable-labels="{{ json_encode($labelsMap) }}"
    class="relative"
>
    {{-- Trigger --}}
    <div
        x-ref="trigger"
        @click="toggle()"
        {{ $attributes->filter(fn($v, $k) => !in_array($k, $skipAttrs) && !str_starts_with($k, 'wire:model'))->merge(['class' => 'w-full text-left border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-zinc-800 text-gray-900 dark:text-white cursor-pointer select-none transition-shadow']) }}
        :class="{ 'opacity-50 cursor-not-allowed pointer-events-none': disabled, 'ring-2 ring-blue-500 border-blue-500': isOpen }"
        role="combobox"
        aria-haspopup="listbox"
        :aria-expanded="isOpen"
        tabindex="0"
    >
        <div class="flex items-center gap-2 px-3 py-2 min-h-[42px]">
            <div class="flex-1 min-w-0">
                {{-- Multi-select tags --}}
                <template x-if="multiple && Array.isArray(selected) && selected.length > 0">
                    <div class="flex flex-wrap gap-1.5">
                        <template x-for="val in selected" :key="val">
                            <span class="inline-flex items-center gap-1 pl-2 pr-1 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 max-w-full">
                                <span class="truncate" x-text="getLabel(val)"></span>
                                <span
                                    @click.stop="removeTag(val)"
                                    class="flex-shrink-0 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-blue-200 dark:hover:bg-blue-800 cursor-pointer transition-colors"
                                    role="button"
                                    aria-label="Remove"
                                >
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </span>
                            </span>
                        </template>
                    </div>
                </template>

                {{-- Single select display --}}
                <template x-if="!multiple && selected !== null">
                    <span class="block truncate" x-text="getLabel(selected)"></span>
                </template>

                {{-- Placeholder --}}
                <template x-if="(multiple && (!Array.isArray(selected) || selected.length === 0)) || (!multiple && selected === null)">
                    <span class="block truncate text-gray-400 dark:text-gray-500">{{ $placeholder }}</span>
                </template>
            </div>

            <div class="flex items-center gap-1 flex-shrink-0">
                {{-- Clear button --}}
                <span
                    x-show="clearable && !disabled && (multiple ? (Array.isArray(selected) && selected.length > 0) : selected !== null)"
                    x-cloak
                    @click.stop="clearAll()"
                    class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer transition-colors"
                    role="button"
                    aria-label="Clear selection"
                    title="Clear"
                >
                    <svg class="w-4 h-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </span>

                {{-- Chevron --}}
                <svg
                    class="w-5 h-5 text-gray-400 flex-shrink-0 transition-transform duration-200"
                    :class="{ 'rotate-180': isOpen }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- Backdrop: closes dropdown on outside click, immune to Livewire morphing --}}
    <div x-show="isOpen" x-cloak class="fixed inset-0 z-[9998]" @click="close()" style="background: transparent;"></div>

    {{-- Dropdown panel teleported to <body> so it is never clipped by a parent
         stacking context, overflow:hidden, or transform on an ancestor element. --}}
    <template x-teleport="body">
    <div
        x-show="isOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        :style="{
            position: 'fixed',
            left:   dropdownX + 'px',
            width:  dropdownWidth + 'px',
            top:    dropdownOpenUpward ? 'auto' : (dropdownY + 'px'),
            bottom: dropdownOpenUpward ? (dropdownBottom + 'px') : 'auto',
            zIndex: 9999,
        }"
        class="origin-top bg-white dark:bg-zinc-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg overflow-hidden"
        role="listbox"
        :aria-multiselectable="multiple"
    >
        <input
            x-ref="searchInput"
            type="text"
            x-model="search"
            @click.stop
            placeholder="{{ $searchPlaceholder }}"
            class="w-full px-3 py-2.5 border-b border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none text-sm"
            aria-label="Search options"
        >

        <div class="max-h-60 overflow-auto overscroll-contain" x-ref="optionsList">
            @if ($grouped)
                <template x-for="group in filteredOptions" :key="group.group">
                    <div>
                        <div
                            class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-zinc-900 uppercase tracking-wider"
                            x-text="group.group"
                        ></div>
                        <template x-for="option in group.items" :key="option.value">
                            <div
                                @click="select(option.value)"
                                class="px-3 py-2.5 cursor-pointer flex items-center justify-between transition-colors"
                                :class="{
                                    'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300': isSelected(option.value),
                                    'hover:bg-gray-100 dark:hover:bg-gray-700': !isSelected(option.value),
                                    'bg-gray-100 dark:bg-gray-700': flatOptions[highlightedIndex]?.value === option.value && !isSelected(option.value)
                                }"
                                :data-highlighted="flatOptions[highlightedIndex]?.value === option.value"
                                role="option"
                                :aria-selected="isSelected(option.value)"
                            >
                                <span x-text="option.label" class="truncate"></span>
                                <svg x-show="isSelected(option.value)" class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </template>
                    </div>
                </template>
                <div
                    x-show="filteredOptions.length === 0"
                    class="px-3 py-3 text-gray-500 dark:text-gray-400 text-sm text-center"
                >{{ $emptyMessage }}</div>
            @else
                <template x-for="(option, index) in filteredOptions" :key="option.value">
                    <div
                        @click="select(option.value)"
                        class="px-3 py-2.5 cursor-pointer flex items-center justify-between transition-colors"
                        :class="{
                            'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300': isSelected(option.value),
                            'hover:bg-gray-100 dark:hover:bg-gray-700': !isSelected(option.value),
                            'bg-gray-100 dark:bg-gray-700': highlightedIndex === index && !isSelected(option.value)
                        }"
                        :data-highlighted="highlightedIndex === index"
                        role="option"
                        :aria-selected="isSelected(option.value)"
                    >
                        <span x-text="option.label" class="truncate"></span>
                        <svg x-show="isSelected(option.value)" class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </template>
                <div
                    x-show="filteredOptions.length === 0"
                    class="px-3 py-3 text-gray-500 dark:text-gray-400 text-sm text-center"
                >{{ $emptyMessage }}</div>
            @endif
        </div>
    </div>
    </template>
</div>
