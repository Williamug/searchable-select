<?php

namespace Williamug\SearchableSelect;

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
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'searchable-select');

        // Register Blade component for <x-searchable-select /> syntax
        $this->loadViewComponentsAs('', [
            'searchable-select' => \Williamug\SearchableSelect\View\Components\SearchableSelect::class,
        ]);
    }
}
