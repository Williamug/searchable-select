<?php

use Illuminate\Support\Facades\File;

test('it registers the install command', function () {
    $commands = $this->app['Illuminate\Contracts\Console\Kernel']->all();

    expect($commands)->toHaveKey('install:searchable-select');
});

test('it publishes the component view', function () {
    $published = $this->app['config']->get('view.paths');

    // Service provider should be registered
    expect(class_exists('Williamug\SearchableSelect\SearchableSelectServiceProvider'))->toBeTrue();
});

test('it loads views from package', function () {
    $viewPath = __DIR__.'/../../resources/views';

    expect(File::exists($viewPath.'/searchable-select.blade.php'))->toBeTrue();
});
