<?php

namespace Williamug\SearchableSelect\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class SearchableSelect extends Component
{
    public function __construct(
        public array|Collection $options = [],
        public string $optionValue = 'id',
        public string $optionLabel = 'name',
        public string $optionSubtitle = '',
        public string $optionIcon = '',
        public string $placeholder = 'Select option',
        public string $searchPlaceholder = 'Search...',
        public string $emptyMessage = 'No options available',
        public bool $multiple = false,
        public bool $clearable = true,
        public bool $disabled = false,
        public bool $grouped = false,
        public string $groupLabel = 'label',
        public string $groupOptions = 'options',
        public bool $searchable = true,
        public string $maxHeight = '240px',
        public int $minLength = 0,
        public string $placement = 'auto',
        public int $maxTags = 0,
        public bool $async = false,
    ) {}

    public function render(): View
    {
        return view('searchable-select::searchable-select');
    }
}
