<?php

test('it loads component from vendor without publishing', function () {
    // Verify component class is registered
    $aliases = app('blade.compiler')->getClassComponentAliases();
    expect($aliases)->toHaveKey('searchable-select');

    // Verify view namespace is registered
    $hints = app('view')->getFinder()->getHints();
    expect($hints)->toHaveKey('searchable-select');

    // Verify view file exists in vendor
    $viewPath = __DIR__.'/../../resources/views/searchable-select.blade.php';
    expect(file_exists($viewPath))->toBeTrue();
});
