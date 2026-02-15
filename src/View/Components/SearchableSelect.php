<?php

namespace Williamug\SearchableSelect\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SearchableSelect extends Component
{
  /**
   * Get the view / contents that represent the component.
   */
  public function render(): View
  {
    return view('searchable-select::searchable-select');
  }
}
