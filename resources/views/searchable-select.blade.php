@php
    $wireModelKey = $attributes->whereStartsWith('wire:model')->first();

    $xModelValue = $attributes->get('x-model');
    $hasXModel   = (bool) $xModelValue;

    $alpineOptions = [];
    if ($grouped) {
        foreach ($options as $group) {
            $groupLabelText = is_array($group) ? $group[$groupLabel] : $group->{$groupLabel};
            $groupItems     = is_array($group) ? $group[$groupOptions] : $group->{$groupOptions};
            $items = [];
            foreach ($groupItems as $opt) {
                $item = [
                    'value' => is_array($opt) ? $opt[$optionValue] : $opt->{$optionValue},
                    'label' => is_array($opt) ? $opt[$optionLabel] : $opt->{$optionLabel},
                ];
                if ($optionSubtitle) $item['subtitle'] = is_array($opt) ? ($opt[$optionSubtitle] ?? null) : ($opt->{$optionSubtitle} ?? null);
                if ($optionIcon)     $item['icon']     = is_array($opt) ? ($opt[$optionIcon]     ?? null) : ($opt->{$optionIcon}     ?? null);
                $items[] = $item;
            }
            $alpineOptions[] = ['group' => $groupLabelText, 'items' => $items];
        }
    } else {
        foreach ($options as $opt) {
            $item = [
                'value' => is_array($opt) ? $opt[$optionValue] : $opt->{$optionValue},
                'label' => is_array($opt) ? $opt[$optionLabel] : $opt->{$optionLabel},
            ];
            if ($optionSubtitle) $item['subtitle'] = is_array($opt) ? ($opt[$optionSubtitle] ?? null) : ($opt->{$optionSubtitle} ?? null);
            if ($optionIcon)     $item['icon']     = is_array($opt) ? ($opt[$optionIcon]     ?? null) : ($opt->{$optionIcon}     ?? null);
            $alpineOptions[] = $item;
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

    $skipAttrs = [
        'options', 'option-value', 'option-label', 'option-subtitle', 'option-icon',
        'placeholder', 'search-placeholder', 'empty-message',
        'multiple', 'clearable', 'disabled', 'grouped', 'group-label', 'group-options',
        'searchable', 'max-height', 'min-length', 'placement', 'max-tags', 'async',
        'x-model',
    ];
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
            searchable: config.searchable ?? true,
            maxHeight: config.maxHeight ?? '240px',
            minLength: config.minLength ?? 0,
            placement: config.placement ?? 'auto',
            maxTags: config.maxTags ?? 0,
            async: config.async ?? false,
            isLoading: false,

            // Dropdown positioning
            dropdownX: 0,
            dropdownY: 0,
            dropdownBottom: 0,
            dropdownWidth: 0,
            dropdownOpenUpward: false,
            dropdownZIndex: 9999,

            _onNavigating: null,
            _onNavigated: null,
            _onScroll: null,
            _onResize: null,
            _onMorphed: null,
            _onDocumentMousedown: null,
            _syncing: false,

            init() {
                if (this.wireModelKey && this.$wire) {
                    try {
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

                this._onScroll = () => { if (this.isOpen) this.updatePosition(); };
                this._onResize = () => { if (this.isOpen) this.updatePosition(); };

                // Refresh options from data attributes after Livewire morphs the DOM.
                // Also clears the async loading state so the spinner goes away.
                this._onMorphed = () => {
                    try {
                        const opts = this.$el.getAttribute('data-searchable-options');
                        const map  = this.$el.getAttribute('data-searchable-labels');
                        if (opts !== null) this.options   = JSON.parse(opts);
                        if (map  !== null) this.labelsMap = JSON.parse(map);
                    } catch (e) {}
                    if (this.async) this.isLoading = false;
                };

                this._onMorphed();

                // Async mode: fire a Livewire event when search changes so the
                // parent component can update $options server-side.
                if (this.async) {
                    this.$watch('search', (value) => {
                        const q = value.trim();
                        if (q.length >= (this.minLength || 0)) {
                            this.isLoading = true;
                            if (this.$wire) {
                                try {
                                    this.$wire.dispatch('searchable-select:search', {
                                        query: q,
                                        key: this.wireModelKey,
                                    });
                                } catch (_) {}
                            }
                        }
                    });
                }

                // Close on mousedown outside the trigger or dropdown panel.
                // Using mousedown (not click) so it fires before focus changes and
                // is not blocked by stopPropagation on inner elements. Capture phase
                // ensures we see the event even if a child handler stops bubbling.
                // This replaces the old transparent full-screen backdrop div, which
                // intercepted scroll/hover events and froze the rest of the page.
                this._onDocumentMousedown = (e) => {
                    if (!this.isOpen) return;
                    if (this.$refs.trigger?.contains(e.target)) return;
                    if (this.$refs.dropdownPanel?.contains(e.target)) return;
                    this.close();
                };

                // Flux (and any library that uses the native <dialog> element with
                // showModal()) places the dialog in the browser's top layer — a
                // rendering surface that sits above ALL other content regardless of
                // z-index. Our dropdown is teleported to <body> and lives in the
                // normal stacking context, so it can never beat the top layer.
                //
                // Fix: if this component is inside a <dialog>, move the already-
                // teleported panel into the dialog after Alpine finishes its init
                // tick. The panel is then in the top layer alongside the dialog and
                // renders above the modal backdrop correctly. position:fixed
                // coordinates still work because fixed positioning in the top layer
                // is always relative to the viewport.
                this.$nextTick(() => {
                    const dialog = this.$el.closest('dialog');
                    if (dialog && this.$refs.dropdownPanel) {
                        dialog.appendChild(this.$refs.dropdownPanel);
                    }
                });

                document.addEventListener('livewire:navigating', this._onNavigating);
                document.addEventListener('livewire:navigated',  this._onNavigated);
                document.addEventListener('livewire:morphed',    this._onMorphed);
                document.addEventListener('mousedown', this._onDocumentMousedown, true);
                window.addEventListener('scroll', this._onScroll, { passive: true, capture: true });
                window.addEventListener('resize', this._onResize, { passive: true });
            },

            destroy() {
                document.removeEventListener('livewire:navigating', this._onNavigating);
                document.removeEventListener('livewire:navigated',  this._onNavigated);
                document.removeEventListener('livewire:morphed',    this._onMorphed);
                document.removeEventListener('mousedown', this._onDocumentMousedown, true);
                window.removeEventListener('scroll', this._onScroll, true);
                window.removeEventListener('resize', this._onResize);
            },

            updatePosition() {
                const trigger = this.$refs.trigger;
                if (!trigger) return;
                const rect = trigger.getBoundingClientRect();
                const spaceBelow = window.innerHeight - rect.bottom;
                const maxH = 304;

                if (this.placement === 'top') {
                    this.dropdownOpenUpward = true;
                } else if (this.placement === 'bottom') {
                    this.dropdownOpenUpward = false;
                } else {
                    this.dropdownOpenUpward = spaceBelow < maxH && rect.top > spaceBelow;
                }

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
                // Async mode: server handles filtering, just return current options.
                if (this.async) {
                    if (this.minLength > 0 && this.search.trim().length > 0 && this.search.trim().length < this.minLength) return [];
                    return this.options;
                }
                const q = this.search.trim();
                if (!q) return this.options;
                // Show all options (no filtering) until minLength is reached.
                if (this.minLength > 0 && q.length < this.minLength) return [];
                const ql = q.toLowerCase();
                if (this.grouped) {
                    return this.options
                        .map(g => ({ ...g, items: g.items.filter(i => i.label.toLowerCase().includes(ql)) }))
                        .filter(g => g.items.length > 0);
                }
                return this.options.filter(o => o.label.toLowerCase().includes(ql));
            },

            get showMinLengthHint() {
                return this.minLength > 0 && this.search.trim().length > 0 && this.search.trim().length < this.minLength;
            },

            get charsNeeded() {
                return this.minLength - this.search.trim().length;
            },

            get visibleTags() {
                if (!Array.isArray(this.selected)) return [];
                if (!this.maxTags) return this.selected;
                return this.selected.slice(0, this.maxTags);
            },

            get hiddenTagCount() {
                if (!Array.isArray(this.selected) || !this.maxTags) return 0;
                return Math.max(0, this.selected.length - this.maxTags);
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

            // Walk up the DOM to find the highest z-index ancestor and float one above
            // it. Handles any stacking-context scenario: Flux modals (~50), third-party
            // modals at z-index 10000+, or CSS transforms that create new stacking
            // contexts. Baseline of 9999 applies when no ancestor exceeds that value.
            _resolveZIndex() {
                let z = 9999;
                let node = this.$el?.parentElement;
                while (node && node !== document.body) {
                    const computed = parseInt(getComputedStyle(node).zIndex);
                    if (!isNaN(computed) && computed > 0) {
                        z = Math.max(z, computed + 1);
                    }
                    node = node.parentElement;
                }
                this.dropdownZIndex = z;
            },

            open() {
                if (this.disabled || this.isOpen) return;
                this._resolveZIndex();
                this.updatePosition();
                this.isOpen = true;
                this.highlightedIndex = -1;
                this.$nextTick(() => {
                    if (this.searchable) {
                        this.$refs.searchInput?.focus();
                    } else {
                        this.$refs.optionsList?.focus();
                    }
                });
            },

            close() {
                if (!this.isOpen) return;
                this.isOpen = false;
                this.search = '';
                this.highlightedIndex = -1;
                this.isLoading = false;
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

            selectAll() {
                if (!this.multiple) return;
                this.selected = this.flatOptions.map(o => o.value);
            },

            deselectAll() {
                if (!this.multiple) return;
                this.selected = [];
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
        searchable: {{ $searchable ? 'true' : 'false' }},
        maxHeight: '{{ $maxHeight }}',
        minLength: {{ $minLength }},
        placement: '{{ $placement }}',
        maxTags: {{ $maxTags }},
        async: {{ $async ? 'true' : 'false' }},
    })"
    @if($hasXModel) x-modelable="selected" x-model="{{ $xModelValue }}" @endif
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
                        <template x-for="val in visibleTags" :key="val">
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
                        <template x-if="hiddenTagCount > 0">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 dark:bg-zinc-700 dark:text-gray-300">
                                +<span x-text="hiddenTagCount"></span> more
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

    {{-- Dropdown panel teleported to <body> so it is never clipped by a parent
         stacking context, overflow:hidden, or transform on an ancestor element.
         Click-outside is handled by a document mousedown listener (see init) rather
         than a full-screen backdrop, so scroll and hover events are never blocked. --}}
    <template x-teleport="body">
    <div
        x-ref="dropdownPanel"
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
            zIndex: dropdownZIndex,
        }"
        class="origin-top bg-white dark:bg-zinc-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg overflow-hidden"
        role="listbox"
        :aria-multiselectable="multiple"
    >
        {{-- Search input --}}
        <template x-if="searchable">
            <input
                x-ref="searchInput"
                type="text"
                x-model="search"
                @click.stop
                @keydown="handleKeydown"
                placeholder="{{ $searchPlaceholder }}"
                class="w-full px-3 py-2.5 border-b border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:outline-none text-sm"
                aria-label="Search options"
            >
        </template>

        {{-- Select all / Deselect all row (multi-select only) --}}
        <template x-if="multiple">
            <div class="flex items-center justify-between px-3 py-1.5 border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-zinc-900">
                <button
                    type="button"
                    @click.stop="selectAll()"
                    class="text-xs font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                >Select all</button>
                <button
                    type="button"
                    @click.stop="deselectAll()"
                    class="text-xs text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 transition-colors"
                >Clear all</button>
            </div>
        </template>

        {{-- Loading spinner (async mode) --}}
        <template x-if="isLoading">
            <div class="px-3 py-4 flex items-center justify-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <svg class="animate-spin w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span>Loading...</span>
            </div>
        </template>

        {{-- Options list --}}
        <div
            x-show="!isLoading"
            :style="'max-height: ' + maxHeight + '; overflow-y: auto; overscroll-behavior: contain;'"
            x-ref="optionsList"
            tabindex="-1"
            @keydown="handleKeydown"
        >
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
                                class="px-3 py-2.5 cursor-pointer flex items-center gap-2.5 transition-colors"
                                :class="{
                                    'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300': isSelected(option.value),
                                    'hover:bg-gray-100 dark:hover:bg-gray-700': !isSelected(option.value),
                                    'bg-gray-100 dark:bg-gray-700': flatOptions[highlightedIndex]?.value === option.value && !isSelected(option.value)
                                }"
                                :data-highlighted="flatOptions[highlightedIndex]?.value === option.value"
                                role="option"
                                :aria-selected="isSelected(option.value)"
                            >
                                <template x-if="option.icon">
                                    <img :src="option.icon" class="w-6 h-6 rounded-full object-cover flex-shrink-0" alt="" />
                                </template>
                                <div class="flex-1 min-w-0">
                                    <span x-text="option.label" class="block truncate"></span>
                                    <template x-if="option.subtitle">
                                        <span x-text="option.subtitle" class="block truncate text-xs text-gray-500 dark:text-gray-400 mt-0.5 leading-tight"></span>
                                    </template>
                                </div>
                                <svg x-show="isSelected(option.value)" class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0 ml-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </template>
                    </div>
                </template>
            @else
                <template x-for="(option, index) in filteredOptions" :key="option.value">
                    <div
                        @click="select(option.value)"
                        class="px-3 py-2.5 cursor-pointer flex items-center gap-2.5 transition-colors"
                        :class="{
                            'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300': isSelected(option.value),
                            'hover:bg-gray-100 dark:hover:bg-gray-700': !isSelected(option.value),
                            'bg-gray-100 dark:bg-gray-700': highlightedIndex === index && !isSelected(option.value)
                        }"
                        :data-highlighted="highlightedIndex === index"
                        role="option"
                        :aria-selected="isSelected(option.value)"
                    >
                        <template x-if="option.icon">
                            <img :src="option.icon" class="w-6 h-6 rounded-full object-cover flex-shrink-0" alt="" />
                        </template>
                        <div class="flex-1 min-w-0">
                            <span x-text="option.label" class="block truncate"></span>
                            <template x-if="option.subtitle">
                                <span x-text="option.subtitle" class="block truncate text-xs text-gray-500 dark:text-gray-400 mt-0.5 leading-tight"></span>
                            </template>
                        </div>
                        <svg x-show="isSelected(option.value)" class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0 ml-auto" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </template>
            @endif

            {{-- Min-length hint --}}
            <div
                x-show="showMinLengthHint"
                x-cloak
                class="px-3 py-3 text-gray-500 dark:text-gray-400 text-sm text-center"
            >Type <span x-text="charsNeeded"></span> more character<span x-show="charsNeeded !== 1">s</span> to search</div>

            {{-- Empty message --}}
            <div
                x-show="!showMinLengthHint && filteredOptions.length === 0"
                class="px-3 py-3 text-gray-500 dark:text-gray-400 text-sm text-center"
            >{{ $emptyMessage }}</div>
        </div>
    </div>
    </template>
</div>
