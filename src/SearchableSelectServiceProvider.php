<?php

namespace Williamug\SearchableSelect;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Williamug\SearchableSelect\View\Components\SearchableSelect;

class SearchableSelectServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/searchable-select.php',
            'searchable-select'
        );
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'searchable-select');

        Blade::component(SearchableSelect::class, 'searchable-select');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/searchable-select.php' => config_path('searchable-select.php'),
            ], 'searchable-select-config');
        }
    }
}
