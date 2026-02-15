<?php

namespace Williamug\SearchableSelect;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SearchableSelectServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    // Load views from the package
    $this->loadViewsFrom(__DIR__ . '/../resources/views', 'searchable-select');

    // Register the searchable-select view as an anonymous component
    Blade::component('searchable-select::searchable-select', 'searchable-select');
  }
}
