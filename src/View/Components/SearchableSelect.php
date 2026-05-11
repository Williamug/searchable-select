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
    public string $placeholder = 'Select option',
    public string $searchPlaceholder = 'Search...',
    public string $emptyMessage = 'No options available',
    public bool $multiple = false,
    public bool $clearable = true,
    public bool $disabled = false,
    public bool $grouped = false,
    public string $groupLabel = 'label',
    public string $groupOptions = 'options',
  ) {}

  public function render(): View
  {
    return view('searchable-select::searchable-select');
  }
}
