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
        'empty-message', 'multiple', 'clearable', 'disabled', 'grouped', 'group-label', 'group-options', 'teleport'];
@endphp

@once
<script>
(function () {
    if (window.__searchableSelectRegistered) return;

    // Inject component styles into <head> so Livewire's morphdom never removes them.
    (function injectStyles() {
        if (document.getElementById('searchable-select-styles')) return;
        const el = document.createElement('style');
        el.id = 'searchable-select-styles';
        el.textContent = [
            '[x-cloak]{display:none!important}',
            '.ss-trigger{display:flex;align-items:center;width:100%;min-height:42px;padding:.375rem .75rem;gap:.5rem;font-size:1rem;font-weight:400;line-height:1.5;color:var(--bs-body-color);background-color:var(--bs-body-bg);border:var(--bs-border-width,1px) solid var(--bs-border-color);border-radius:var(--bs-border-radius,.375rem);cursor:pointer;user-select:none;transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out;overflow:hidden}',
            '.ss-trigger.ss-open{border-color:#86b7fe;outline:0;box-shadow:0 0 0 .25rem rgba(13,110,253,.25)}',
            '.ss-trigger.ss-disabled{background-color:var(--bs-secondary-bg);opacity:.65;cursor:not-allowed;pointer-events:none}',
            '.ss-content{flex:1 1 auto;min-width:0;overflow:hidden}',
            '.ss-tags{display:flex;flex-wrap:wrap;gap:.25rem}',
            '.ss-tag{display:inline-flex;align-items:center;gap:.125rem;padding:.125rem .25rem .125rem .5rem;border-radius:.25rem;font-size:.75rem;font-weight:500;background-color:var(--bs-primary-bg-subtle,#cfe2ff);color:var(--bs-primary-text-emphasis,#052c65);max-width:100%}',
            '.ss-tag-label{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}',
            '.ss-tag-remove{display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;width:1rem;height:1rem;padding:0;border:none;background:none;color:inherit;cursor:pointer;border-radius:50%;opacity:.7;line-height:1}',
            '.ss-tag-remove:hover{opacity:1;background-color:rgba(var(--bs-primary-rgb,13,110,253),.2)}',
            '.ss-placeholder{display:block;color:var(--bs-secondary-color,#6c757d);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}',
            '.ss-controls{display:flex;align-items:center;gap:.25rem;flex-shrink:0}',
            '.ss-clear-btn{display:inline-flex;align-items:center;justify-content:center;width:1.25rem;height:1.25rem;padding:0;border:none;background:none;color:var(--bs-secondary-color,#6c757d);cursor:pointer;border-radius:.2rem;transition:color .1s ease,background-color .1s ease;line-height:1}',
            '.ss-clear-btn:hover{color:var(--bs-body-color);background-color:var(--bs-secondary-bg)}',
            '.ss-chevron{color:var(--bs-secondary-color,#6c757d);flex-shrink:0;transition:transform .2s ease}',
            '.ss-chevron.ss-open{transform:rotate(180deg)}',
            '.ss-backdrop{position:fixed;inset:0;z-index:9998}',
            '.ss-panel{background-color:var(--bs-body-bg);border:var(--bs-border-width,1px) solid var(--bs-border-color);border-radius:var(--bs-border-radius,.375rem);box-shadow:0 .5rem 1rem rgba(0,0,0,.15);overflow:hidden}',
            '.ss-search{display:block;width:100%;padding:.5rem .75rem;font-size:.875rem;color:var(--bs-body-color);background-color:var(--bs-body-bg);border:none;border-bottom:var(--bs-border-width,1px) solid var(--bs-border-color);outline:none}',
            '.ss-search::placeholder{color:var(--bs-secondary-color,#6c757d)}',
            '.ss-options-list{max-height:240px;overflow-y:auto;overscroll-behavior:contain}',
            '.ss-group-header{padding:.375rem .75rem;font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--bs-secondary-color,#6c757d);background-color:var(--bs-secondary-bg,#e9ecef)}',
            '.ss-option{display:flex;align-items:center;justify-content:space-between;padding:.5rem .75rem;cursor:pointer;color:var(--bs-body-color);transition:background-color .1s ease}',
            '.ss-option:hover{background-color:var(--bs-tertiary-bg,#f8f9fa)}',
            '.ss-option.ss-selected{background-color:var(--bs-primary-bg-subtle,#cfe2ff);color:var(--bs-primary-text-emphasis,#052c65)}',
            '.ss-option.ss-highlighted:not(.ss-selected){background-color:var(--bs-secondary-bg,#e9ecef)}',
            '.ss-check-icon{color:var(--bs-primary,#0d6efd);flex-shrink:0;margin-left:.5rem}',
            '.ss-empty{padding:.75rem;text-align:center;font-size:.875rem;color:var(--bs-secondary-color,#6c757d)}',
        ].join('');
        (document.head || document.documentElement).appendChild(el);
    })();

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
                const maxH = 304;
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
    class="position-relative"
>
    {{-- Trigger --}}
    <div
        x-ref="trigger"
        @click="toggle()"
        {{ $attributes->filter(fn($v, $k) => !in_array($k, $skipAttrs) && !str_starts_with($k, 'wire:model'))->merge(['class' => 'ss-trigger']) }}
        :class="{ 'ss-open': isOpen, 'ss-disabled': disabled }"
        role="combobox"
        aria-haspopup="listbox"
        :aria-expanded="isOpen"
        tabindex="0"
    >
        <div class="ss-content">
            {{-- Multi-select tags --}}
            <template x-if="multiple && Array.isArray(selected) && selected.length > 0">
                <div class="ss-tags">
                    <template x-for="val in selected" :key="val">
                        <span class="ss-tag">
                            <span class="ss-tag-label" x-text="getLabel(val)"></span>
                            <button
                                type="button"
                                @click.stop="removeTag(val)"
                                class="ss-tag-remove"
                                aria-label="Remove"
                            >
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </span>
                    </template>
                </div>
            </template>

            {{-- Single select display --}}
            <template x-if="!multiple && selected !== null">
                <span class="d-block text-truncate" x-text="getLabel(selected)"></span>
            </template>

            {{-- Placeholder --}}
            <template x-if="(multiple && (!Array.isArray(selected) || selected.length === 0)) || (!multiple && selected === null)">
                <span class="ss-placeholder">{{ $placeholder }}</span>
            </template>
        </div>

        <div class="ss-controls">
            {{-- Clear button --}}
            <button
                type="button"
                x-show="clearable && !disabled && (multiple ? (Array.isArray(selected) && selected.length > 0) : selected !== null)"
                x-cloak
                @click.stop="clearAll()"
                class="ss-clear-btn"
                aria-label="Clear selection"
                title="Clear"
            >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Chevron --}}
            <svg
                class="ss-chevron"
                :class="{ 'ss-open': isOpen }"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>

    {{-- Backdrop: closes dropdown on outside click, immune to Livewire morphing --}}
    <div x-show="isOpen" x-cloak class="ss-backdrop" @click="close()"></div>

    {{-- Dropdown panel teleported to <body> so it is never clipped by a parent
         stacking context, overflow:hidden, or transform on an ancestor element. --}}
    @if($teleport)
        <template x-teleport="body">
    @endif
    <div
        x-show="isOpen"
        x-cloak
        x-transition
        :style="{
            position: 'fixed',
            left:   dropdownX + 'px',
            width:  dropdownWidth + 'px',
            top:    dropdownOpenUpward ? 'auto' : (dropdownY + 'px'),
            bottom: dropdownOpenUpward ? (dropdownBottom + 'px') : 'auto',
            zIndex: 9999,
        }"
        class="ss-panel"
        role="listbox"
        :aria-multiselectable="multiple"
    >
        <input
            x-ref="searchInput"
            type="text"
            x-model="search"
            @click.stop
            placeholder="{{ $searchPlaceholder }}"
            class="ss-search"
            aria-label="Search options"
        >

        <div class="ss-options-list" x-ref="optionsList">
            @if ($grouped)
                <template x-for="group in filteredOptions" :key="group.group">
                    <div>
                        <div class="ss-group-header" x-text="group.group"></div>
                        <template x-for="option in group.items" :key="option.value">
                            <div
                                @click="select(option.value)"
                                class="ss-option"
                                :class="{
                                    'ss-selected': isSelected(option.value),
                                    'ss-highlighted': flatOptions[highlightedIndex]?.value === option.value
                                }"
                                :data-highlighted="flatOptions[highlightedIndex]?.value === option.value"
                                role="option"
                                :aria-selected="isSelected(option.value)"
                            >
                                <span class="text-truncate" x-text="option.label"></span>
                                <svg x-show="isSelected(option.value)" class="ss-check-icon" width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </template>
                    </div>
                </template>
                <div x-show="filteredOptions.length === 0" class="ss-empty">{{ $emptyMessage }}</div>
            @else
                <template x-for="(option, index) in filteredOptions" :key="option.value">
                    <div
                        @click="select(option.value)"
                        class="ss-option"
                        :class="{
                            'ss-selected': isSelected(option.value),
                            'ss-highlighted': highlightedIndex === index
                        }"
                        :data-highlighted="highlightedIndex === index"
                        role="option"
                        :aria-selected="isSelected(option.value)"
                    >
                        <span class="text-truncate" x-text="option.label"></span>
                        <svg x-show="isSelected(option.value)" class="ss-check-icon" width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </template>
                <div x-show="filteredOptions.length === 0" class="ss-empty">{{ $emptyMessage }}</div>
            @endif
        </div>
    </div>
    @if($teleport)
        </template>
    @endif
</div>
