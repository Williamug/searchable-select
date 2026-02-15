<?php

namespace Williamug\SearchableSelect;

use Williamug\SearchableSelect\Commands\InstallSearchableSelectCommand;
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
    // Register the installation command
    if ($this->app->runningInConsole()) {
      $this->commands([
        InstallSearchableSelectCommand::class,
      ]);
    }

    // Publish the component view
    $this->publishes([
      __DIR__ . '/../resources/views/searchable-select.blade.php' => resource_path('views/components/searchable-select.blade.php'),
    ], 'searchable-select-component');

    // Load views from the package
    $this->loadViewsFrom(__DIR__ . '/../resources/views', 'searchable-select');
  }
}
