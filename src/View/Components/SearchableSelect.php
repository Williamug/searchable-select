<?php

namespace Williamug\SearchableSelect\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SearchableSelect extends Component
{
    public string $currentTheme;

    public function __construct(?string $theme = null)
    {
        $this->currentTheme = $theme ?? config('searchable-select.theme', 'tailwind');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|\Closure|string
    {
        if ($this->currentTheme === 'bootstrap') {
            return view('searchable-select::searchable-select-bootstrap');
        }

        return view('searchable-select::searchable-select');
    }
}
