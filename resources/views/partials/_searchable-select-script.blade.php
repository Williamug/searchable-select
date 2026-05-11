@once
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endonce

@once
@push('scripts')
<script>
    if (!window.searchableSelect) {
        window.searchableSelect = function (config) {
            return {
                open: false,
                search: '',
                loading: false,
                error: null,
                highlightedIndex: -1,
                multiple: config.multiple ?? false,
                clearable: config.clearable ?? true,
                disabled: config.disabled ?? false,
                apiUrl: config.apiUrl ?? null,
                apiSearchParam: config.apiSearchParam ?? 'search',
                optionValueKey: config.optionValueKey ?? 'id',
                optionLabelKey: config.optionLabelKey ?? 'name',
                options: config.options ?? [],
                selectedValues: config.selectedValues ?? [],
                labelsMap: config.labelsMap ?? {},
                wireModel: config.wireModel ?? '',
                grouped: config.grouped ?? false,

                get flatOptions() {
                    if (this.grouped) {
                        return this.filteredOptions.flatMap(g => g.items);
                    }
                    return this.filteredOptions;
                },

                get filteredOptions() {
                    if (this.apiUrl || !this.search) return this.options;
                    const query = this.search.toLowerCase();
                    if (this.grouped) {
                        return this.options.map(group => ({
                            ...group,
                            items: group.items.filter(item => item.label.toLowerCase().includes(query))
                        })).filter(group => group.items.length > 0);
                    }
                    return this.options.filter(opt => opt.label.toLowerCase().includes(query));
                },

                getLabel(value) {
                    return this.labelsMap[value] ?? this.labelsMap[String(value)] ?? String(value);
                },

                isSelected(value) {
                    return this.selectedValues.some(v => String(v) === String(value));
                },

                openDropdown() {
                    if (this.disabled) return;
                    this.open = true;
                    this.highlightedIndex = -1;
                    this.$nextTick(() => {
                        if (this.$refs.searchInput) this.$refs.searchInput.focus();
                    });
                    if (this.apiUrl && this.options.length === 0) {
                        this.searchApi();
                    }
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
                        const index = this.selectedValues.findIndex(v => String(v) === String(value));
                        if (index > -1) {
                            this.selectedValues.splice(index, 1);
                        } else {
                            this.selectedValues.push(value);
                        }
                        $wire.set(this.wireModel, [...this.selectedValues]);
                    } else {
                        this.selectedValues = [value];
                        $wire.set(this.wireModel, value);
                        this.closeDropdown();
                    }
                },

                removeSelection(value) {
                    const index = this.selectedValues.findIndex(v => String(v) === String(value));
                    if (index > -1) {
                        this.selectedValues.splice(index, 1);
                        $wire.set(this.wireModel, this.multiple ? [...this.selectedValues] : null);
                    }
                },

                clearAll() {
                    this.selectedValues = [];
                    $wire.set(this.wireModel, this.multiple ? [] : null);
                    this.search = '';
                    this.error = null;
                },

                async searchApi() {
                    if (!this.apiUrl) return;
                    this.loading = true;
                    this.error = null;
                    try {
                        const url = new URL(this.apiUrl, window.location.origin);
                        url.searchParams.set(this.apiSearchParam, this.search);
                        const response = await fetch(url);
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}`);
                        }
                        const data = await response.json();
                        const items = (data.data || data).map(item => ({
                            value: item[this.optionValueKey],
                            label: item[this.optionLabelKey]
                        }));
                        this.options = items;
                        items.forEach(item => {
                            this.labelsMap[String(item.value)] = item.label;
                        });
                    } catch (error) {
                        this.error = 'Search failed. Please try again.';
                    } finally {
                        this.loading = false;
                    }
                }
            };
        };
    }
</script>
@endpush
@endonce
