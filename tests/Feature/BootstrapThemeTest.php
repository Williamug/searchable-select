<?php

use Illuminate\Support\Facades\Config;
use Illuminate\View\ComponentAttributeBag;

$bootstrapViewData = function (array $overrides = []) {
  return array_merge([
    'options' => [
      ['id' => 1, 'name' => 'Option 1'],
      ['id' => 2, 'name' => 'Option 2'],
    ],
    'wireModel' => 'selectedId',
    'selectedValue' => null,
    'placeholder' => 'Select option',
    'searchPlaceholder' => 'Search...',
    'disabled' => false,
    'emptyMessage' => 'No options available',
    'optionValue' => 'id',
    'optionLabel' => 'name',
    'multiple' => false,
    'clearable' => true,
    'apiUrl' => null,
    'apiSearchParam' => 'search',
    'grouped' => false,
    'groupLabel' => 'label',
    'groupOptions' => 'options',
    'theme' => null,
    'attributes' => new ComponentAttributeBag([]),
  ], $overrides);
};

it('uses tailwind theme by default', function () use ($bootstrapViewData) {
  $html = view('searchable-select::searchable-select', $bootstrapViewData())->render();

  expect($html)
    ->toContain('border-gray-300')
    ->toContain('dark:border-gray-600')
    ->toContain('rounded-lg');
});

it('renders bootstrap view with correct classes', function () use ($bootstrapViewData) {
  $html = view('searchable-select::searchable-select-bootstrap', $bootstrapViewData())->render();

  expect($html)
    ->toContain('form-control')
    ->toContain('dropdown-menu')
    ->not->toContain('border-gray-300')
    ->not->toContain('rounded-lg');
});

it('component class routes to bootstrap view when configured', function () {
  Config::set('searchable-select.theme', 'bootstrap');

  $component = new \Williamug\SearchableSelect\View\Components\SearchableSelect();

  expect($component->currentTheme)->toBe('bootstrap');
});

it('component class routes to tailwind view by default', function () {
  Config::set('searchable-select.theme', 'tailwind');

  $component = new \Williamug\SearchableSelect\View\Components\SearchableSelect();

  expect($component->currentTheme)->toBe('tailwind');
});

it('component class accepts theme override', function () {
  Config::set('searchable-select.theme', 'tailwind');

  $component = new \Williamug\SearchableSelect\View\Components\SearchableSelect(theme: 'bootstrap');

  expect($component->currentTheme)->toBe('bootstrap');
});

it('renders bootstrap multi-select with badges', function () use ($bootstrapViewData) {
  $html = view('searchable-select::searchable-select-bootstrap', $bootstrapViewData([
    'options' => [
      ['id' => 1, 'name' => 'Option 1'],
      ['id' => 2, 'name' => 'Option 2'],
      ['id' => 3, 'name' => 'Option 3'],
    ],
    'wireModel' => 'selectedIds',
    'selectedValue' => [1, 2],
    'multiple' => true,
  ]))->render();

  expect($html)
    ->toContain('badge bg-primary rounded-pill')
    ->toContain('multiple: true');
});

it('renders bootstrap grouped options correctly', function () use ($bootstrapViewData) {
  $html = view('searchable-select::searchable-select-bootstrap', $bootstrapViewData([
    'options' => [
      [
        'label' => 'Group 1',
        'options' => [
          ['id' => 1, 'name' => 'Option 1'],
          ['id' => 2, 'name' => 'Option 2'],
        ],
      ],
      [
        'label' => 'Group 2',
        'options' => [
          ['id' => 3, 'name' => 'Option 3'],
        ],
      ],
    ],
    'grouped' => true,
  ]))->render();

  expect($html)
    ->toContain('dropdown-header')
    ->toContain('bg-light');
});
