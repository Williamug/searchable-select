<?php

namespace Williamug\SearchableSelect;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Williamug\SearchableSelect\View\Components\SearchableSelect;

class SearchableSelectServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'searchable-select');

        Blade::component(SearchableSelect::class, 'searchable-select');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/searchable-select'),
            ], 'searchable-select-views');
        }
    }
}
