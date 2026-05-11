<?php

use Illuminate\Support\ServiceProvider;
use Williamug\SearchableSelect\SearchableSelectServiceProvider;

test('service provider registers the component alias', function () {
    $aliases = app('blade.compiler')->getClassComponentAliases();
    expect($aliases)->toHaveKey('searchable-select');
});

test('view namespace resolves correctly', function () {
    expect(app('view')->getFinder()->getHints())->toHaveKey('searchable-select');

    $viewPath = realpath(__DIR__.'/../../resources/views/searchable-select.blade.php');
    expect($viewPath)->not->toBeFalse();
    expect(file_exists($viewPath))->toBeTrue();
});

test('publishable views tag is registered', function () {
    $paths = ServiceProvider::pathsToPublish(
        SearchableSelectServiceProvider::class,
        'searchable-select-views'
    );
    expect($paths)->not->toBeEmpty();
});
