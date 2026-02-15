<?php

namespace Williamug\SearchableSelect;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Williamug\SearchableSelect\Commands\SearchableSelectCommand;

class SearchableSelectServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('searchable-select')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_searchable_select_table')
            ->hasCommand(SearchableSelectCommand::class);
    }
}
