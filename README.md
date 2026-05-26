# Livewire Searchable Select

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ajjitech/searchable-select.svg?style=flat-square)](https://packagist.org/packages/ajjitech/searchable-select)
[![run-tests](https://github.com/ajjitech/searchable-select/actions/workflows/run-tests.yml/badge.svg)](https://github.com/ajjitech/searchable-select/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ajjitech/searchable-select.svg?style=flat-square)](https://packagist.org/packages/ajjitech/searchable-select)
[![License](https://img.shields.io/packagist/l/ajjitech/searchable-select.svg?style=flat-square)](https://packagist.org/packages/ajjitech/searchable-select)

A powerful, feature-rich searchable dropdown component for Laravel Livewire 3 & 4 applications. Styled with **Bootstrap 5** and powered by **Alpine.js** (bundled with Livewire) — no jQuery, no extra JavaScript dependencies.

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Bootstrap Setup](#bootstrap-setup)
- [Quick Start](#quick-start)
- [Component Props Reference](#component-props-reference)
- [Usage Examples](#usage-examples)
  - [Basic Single Select](#basic-single-select)
  - [Multi-Select](#multi-select)
  - [Dependent/Cascading Dropdowns](#dependentcascading-dropdowns)
  - [Grouped Options](#grouped-options)
  - [Custom Keys](#custom-keys)
  - [Bootstrap Modal](#bootstrap-modal)
  - [With Validation](#with-validation)
  - [Disabled State](#disabled-state)
  - [Without Clear Button](#without-clear-button)
- [Advanced Features](#advanced-features)
- [Customization Guide](#customization-guide)
- [Dark Mode](#dark-mode)
- [Troubleshooting](#troubleshooting)
- [Performance Optimization](#performance-optimization)
- [Testing](#testing)
- [Demo Application](#demo-application)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Features

- **Real-time search** — client-side filtering as you type
- **Multi-select support** — select multiple options with visual badge tags
- **Grouped options** — organize options into labeled categories
- **Clear button** — one-click to clear all selections
- **Dark mode support** — automatic via Bootstrap 5.3 CSS variables (`data-bs-theme="dark"`)
- **Accessible** — full keyboard navigation and ARIA attributes
- **Livewire 3 & 4 compatible** — works seamlessly with both versions
- **Responsive** — mobile-friendly and touch-optimized
- **Disabled state** — conditional disabling support
- **Flexible data** — works with Eloquent models, arrays, collections
- **Dependent dropdowns** — perfect for cascading country → region → city selects
- **Bootstrap-native** — uses Bootstrap CSS variables, no extra CSS framework required
- **Zero extra JS** — Alpine.js is bundled with Livewire; no jQuery, no CDN scripts

## Requirements

- **PHP**: 8.2 or higher
- **Laravel**: 11.x, 12.x, or 13.x
- **Livewire**: 3.x or 4.x
- **Bootstrap**: 5.3+
- **Alpine.js**: bundled with Livewire (no separate install needed)

## Installation

Install the package via Composer:

```bash
composer require ajjitech/searchable-select
```

The package automatically registers its service provider. You can start using it immediately.

To customize the component HTML, publish the view:

```bash
php artisan vendor:publish --tag=searchable-select-views
```

This copies the view to `resources/views/vendor/searchable-select/searchable-select.blade.php`.

## Bootstrap Setup

The component is styled entirely with Bootstrap 5.3 CSS variables and scoped `.ss-*` custom classes embedded in the view. **You do not need to configure any content scanning or build step for the component styles** — they are injected inline via `@once` automatically.

You simply need Bootstrap 5.3+ loaded in your application.

### Option A — npm (recommended)

```bash
npm install bootstrap
```

Import in your `resources/js/app.js`:

```js
import 'bootstrap/dist/css/bootstrap.min.css';
// Bootstrap JS is optional — the component uses Alpine.js for all interactivity
```

Or in your `resources/css/app.css`:

```css
@import "bootstrap/dist/css/bootstrap.min.css";
```

### Option B — CDN

Add Bootstrap's CSS to your layout `<head>`:

```html
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
  integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
  crossorigin="anonymous"
>
```

> **Note:** Bootstrap JS (`bootstrap.bundle.min.js`) is **not required**. The component manages all its own interactivity through Alpine.js.

## Quick Start

### Basic Usage

**Step 1: Create a Livewire component**

```bash
php artisan make:livewire ContactForm
```

**Step 2: Set up the component class**

```php
<?php

namespace App\Livewire;

use App\Models\Country;
use Livewire\Component;

class ContactForm extends Component
{
    public $countries;
    public $country_id;

    public function mount()
    {
        $this->countries = Country::orderBy('name')->get();
    }

    public function save()
    {
        $this->validate([
            'country_id' => 'required|exists:countries,id',
        ]);
        // Save your data...
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
```

**Step 3: Use the component in your Blade view**

```blade
<div>
    <label class="form-label">Country</label>

    <x-searchable-select
        wire:model="country_id"
        :options="$countries"
        placeholder="Select a country"
        search-placeholder="Type to search countries..."
    />

    @error('country_id')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror

    <button wire:click="save" class="btn btn-primary mt-3">Save</button>
</div>
```

That's it. The component syncs with your Livewire property via `wire:model` automatically.

## Component Props Reference

| Prop | Type | Default | Required | Description |
|------|------|---------|----------|-------------|
| `options` | Array/Collection | `[]` | Yes | Options data source |
| `placeholder` | String | `'Select option'` | No | Text shown when nothing is selected |
| `searchPlaceholder` | String | `'Search...'` | No | Placeholder for the search input |
| `emptyMessage` | String | `'No options available'` | No | Message shown when the list is empty |
| `optionValue` | String | `'id'` | No | Key to use as the option value |
| `optionLabel` | String | `'name'` | No | Key to use as the display label |
| `multiple` | Boolean | `false` | No | Enable multi-select mode |
| `clearable` | Boolean | `true` | No | Show/hide the × clear button |
| `disabled` | Boolean | `false` | No | Disable the dropdown |
| `grouped` | Boolean | `false` | No | Enable grouped options mode |
| `groupLabel` | String | `'label'` | No | Key for group label (when `grouped` is true) |
| `groupOptions` | String | `'options'` | No | Key for group items array (when `grouped` is true) |
| `teleport` | Boolean | `true` | No | Teleport the dropdown panel to `<body>` to avoid clipping by parent containers. Set to `false` inside Bootstrap modals. |

> **`wire:model`** is a standard Livewire directive. Pass it as `wire:model="propertyName"` and the component handles two-way binding automatically.

## Usage Examples

### Basic Single Select

```php
class UserProfile extends Component
{
    public $countries;
    public $country_id;

    public function mount()
    {
        $this->countries = Country::orderBy('name')->get();
    }
}
```

```blade
<x-searchable-select
    wire:model="country_id"
    :options="$countries"
    placeholder="Select your country"
    search-placeholder="Search countries..."
/>
```

### Multi-Select

Select multiple options displayed as removable badge tags:

```php
class UserSkills extends Component
{
    public $skills;
    public array $selected_skills = [];

    public function mount()
    {
        $this->skills = Skill::orderBy('name')->get();
    }
}
```

```blade
<x-searchable-select
    wire:model="selected_skills"
    :options="$skills"
    :multiple="true"
    placeholder="Select your skills"
/>

@if (!empty($selected_skills))
    <p class="mt-2 text-muted small">{{ count($selected_skills) }} skills selected</p>
@endif
```

Selected items appear as Bootstrap-styled badge tags with × remove buttons.

### Dependent/Cascading Dropdowns

```php
class LocationSelector extends Component
{
    public $countries;
    public $regions = [];
    public $cities  = [];

    public $country_id;
    public $region_id;
    public $city_id;

    public function mount()
    {
        $this->countries = Country::orderBy('name')->get();
    }

    public function updatedCountryId($value)
    {
        $this->regions   = Region::where('country_id', $value)->orderBy('name')->get();
        $this->region_id = null;
        $this->city_id   = null;
        $this->cities    = [];
    }

    public function updatedRegionId($value)
    {
        $this->cities  = City::where('region_id', $value)->orderBy('name')->get();
        $this->city_id = null;
    }
}
```

```blade
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Country</label>
        <x-searchable-select wire:model="country_id" :options="$countries" placeholder="Select Country" />
    </div>

    <div class="col-md-4">
        <label class="form-label">Region</label>
        <x-searchable-select
            wire:model="region_id"
            :options="$regions"
            :placeholder="empty($regions) ? 'First select a country' : 'Select Region'"
            :disabled="!$country_id"
        />
    </div>

    <div class="col-md-4">
        <label class="form-label">City</label>
        <x-searchable-select
            wire:model="city_id"
            :options="$cities"
            :placeholder="empty($cities) ? 'First select a region' : 'Select City'"
            :disabled="!$region_id"
        />
    </div>
</div>
```

### Grouped Options

```php
public $locations = [
    [
        'label'   => 'North America',
        'options' => [
            ['id' => 1, 'name' => 'United States'],
            ['id' => 2, 'name' => 'Canada'],
            ['id' => 3, 'name' => 'Mexico'],
        ],
    ],
    [
        'label'   => 'Europe',
        'options' => [
            ['id' => 4, 'name' => 'United Kingdom'],
            ['id' => 5, 'name' => 'France'],
            ['id' => 6, 'name' => 'Germany'],
        ],
    ],
];
```

```blade
<x-searchable-select
    wire:model="country_id"
    :options="$locations"
    :grouped="true"
    placeholder="Select a country"
/>
```

**Custom group keys:**

```php
public $categories = [
    [
        'category_name' => 'Fruits',
        'items' => [
            ['code' => 'APL', 'title' => 'Apple'],
            ['code' => 'BAN', 'title' => 'Banana'],
        ],
    ],
];
```

```blade
<x-searchable-select
    wire:model="selected_item"
    :options="$categories"
    :grouped="true"
    group-label="category_name"
    group-options="items"
    option-value="code"
    option-label="title"
/>
```

### Custom Keys

```php
public $products = [
    ['sku' => 'PROD-001', 'product_name' => 'Laptop'],
    ['sku' => 'PROD-002', 'product_name' => 'Mouse'],
];

public $selected_sku;
```

```blade
<x-searchable-select
    wire:model="selected_sku"
    :options="$products"
    option-value="sku"
    option-label="product_name"
    placeholder="Select a product"
/>
```

### Bootstrap Modal

When using the component inside a Bootstrap modal, disable teleporting so the dropdown stays inside the modal's DOM and follows Bootstrap's focus and stacking context correctly.

```blade
<div class="modal fade" id="demoModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <label class="form-label">Country Inside Modal</label>

                <x-searchable-select
                    wire:model.live="selectedCountry"
                    :options="$countries"
                    option-label="name"
                    option-value="id"
                    placeholder="Search country..."
                    :teleport="false"
                />
            </div>
        </div>
    </div>
</div>
```

### With Validation

```php
public function save()
{
    $this->validate([
        'country_id' => 'required|exists:countries,id',
    ]);
}
```

```blade
<div class="mb-3">
    <label class="form-label">Country <span class="text-danger">*</span></label>
    <x-searchable-select wire:model="country_id" :options="$countries" />
    @error('country_id')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

<button wire:click="save" class="btn btn-primary">Save</button>
```

**Real-time validation:**

```php
public function updated($propertyName)
{
    $this->validateOnly($propertyName);
}
```

### Disabled State

```blade
<x-searchable-select
    wire:model="region_id"
    :options="$regions"
    :disabled="!$country_id"
    placeholder="First select a country"
/>
```

### Without Clear Button

```blade
<x-searchable-select
    wire:model="country_id"
    :options="$countries"
    :clearable="false"
/>
```

### Using Arrays Instead of Models

```php
public $statuses = [
    ['id' => 'draft',     'name' => 'Draft'],
    ['id' => 'published', 'name' => 'Published'],
    ['id' => 'archived',  'name' => 'Archived'],
];
```

```blade
<x-searchable-select wire:model="status" :options="$statuses" />
```

## Advanced Features

### Adding Extra CSS Classes

Extra attributes (including `class`) are forwarded to the trigger element:

```blade
<x-searchable-select
    wire:model="country_id"
    :options="$countries"
    class="border-primary"
/>
```

### Creating Specialized Components

**resources/views/components/country-select.blade.php:**

```blade
@props(['wireModel'])

<x-searchable-select
    wire:model="{{ $wireModel }}"
    :options="\App\Models\Country::orderBy('name')->get()"
    placeholder="Select a country"
    search-placeholder="Search countries..."
    {{ $attributes }}
/>
```

**Usage:**

```blade
<x-country-select wire-model="country_id" />
```

### Server-Side Search (Large Datasets)

For thousands of records, implement server-side search in Livewire:

```php
public $searchTerm = '';
public $countries  = [];

public function updatedSearchTerm($value)
{
    $this->countries = Country::where('name', 'like', "%{$value}%")
        ->limit(50)
        ->get();
}
```

## Customization Guide

### Publishing Views

```bash
php artisan vendor:publish --tag=searchable-select-views
```

Your copy lands in `resources/views/vendor/searchable-select/searchable-select.blade.php`. Laravel uses your copy instead of the package default.

All component styles are scoped under `.ss-*` CSS classes defined in the `@once` style block at the top of the view. You can override any of them after publishing.

### Overriding Individual Styles

After publishing the view, you can add custom CSS in your `app.css`:

```css
/* Make the trigger taller */
.ss-trigger {
    min-height: 48px;
}

/* Change tag badge color */
.ss-tag {
    background-color: var(--bs-success-bg-subtle);
    color: var(--bs-success-text-emphasis);
}

/* Change selected option highlight */
.ss-option.ss-selected {
    background-color: var(--bs-success-bg-subtle);
    color: var(--bs-success-text-emphasis);
}
```

## Dark Mode

Dark mode works automatically via Bootstrap 5.3's CSS variable system. Add `data-bs-theme="dark"` to your `<html>` element:

```html
<html data-bs-theme="dark">
```

Bootstrap's CSS variables (`--bs-body-bg`, `--bs-body-color`, `--bs-border-color`, `--bs-primary-bg-subtle`, etc.) adjust automatically, and the component adapts with them. No additional configuration needed.

## Troubleshooting

### Dropdown doesn't open / Click doesn't work

1. Verify Alpine.js is loaded (it comes bundled with Livewire 3+):
   ```blade
   @livewireScripts {{-- This includes Alpine.js --}}
   ```

2. Ensure you are **not** loading Alpine.js separately when using Livewire 3+:
   ```html
   <!-- Remove this if you have Livewire 3+ -->
   <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
   ```

3. Check the browser console for JavaScript errors (F12 → Console).

### Component appears unstyled

1. Ensure Bootstrap 5.3+ CSS is loaded **before** your page content renders.
2. Verify Bootstrap's `dist/css/bootstrap.min.css` is included, not just Bootstrap JS.
3. Clear the Laravel view cache:
   ```bash
   php artisan view:clear
   ```

### Options not updating / Stale data

Use `wire:key` when rendering multiple components in loops:

```blade
@foreach($forms as $form)
    <x-searchable-select
        wire:key="country-{{ $form->id }}"
        wire:model="forms.{{ $loop->index }}.country_id"
        :options="$countries"
    />
@endforeach
```

### Multi-select not working

Initialize the Livewire property as an array:

```php
// Correct
public array $selected_items = [];

// Wrong — null, not an array
public $selected_items;
```

Enable multiple mode in the blade:

```blade
<x-searchable-select :multiple="true" wire:model="selected_items" :options="$items" />
```

### Selected value not displaying

Verify the selected value exists in your options and the `optionValue` key matches:

```php
// If your data uses 'code' instead of 'id'
$countries = [['code' => 'US', 'name' => 'USA']];
```

```blade
<x-searchable-select
    wire:model="country_code"
    :options="$countries"
    option-value="code"
/>
```

## Performance Optimization

| Options Count | Recommended Approach |
|--------------|---------------------|
| < 100 | Client-side filtering (default) — works perfectly |
| 100 – 1,000 | Client-side filtering with `wire:key` — still performant |
| 1,000+ | Server-side search with Livewire `updated*` hooks |

**Server-side search:**

```php
public $searchTerm = '';
public $products   = [];

public function updatedSearchTerm($value)
{
    $this->products = Product::where('name', 'like', "%{$value}%")
        ->limit(50)
        ->get();
}
```

**Select only needed columns:**

```php
// Good — only id and name
$this->users = User::select('id', 'name')->get();
```

**Cache static options:**

```php
public function mount()
{
    $this->countries = Cache::remember('countries', 3600, fn () =>
        Country::orderBy('name')->get()
    );
}
```

## Testing

The package includes a comprehensive test suite.

```bash
# Run all tests
composer test

# Run with coverage
composer test -- --coverage

# Run specific test file
./vendor/bin/pest tests/Feature/ComponentTest.php
```

## Demo Application

The package includes a full-featured demo application.

```bash
cd demo
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Visit `http://localhost:8000`

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Make your changes and add tests
4. Run tests and code style checks:
   ```bash
   composer test
   composer format
   ```
5. Commit and open a Pull Request

### Code Style

- **Laravel Pint** for PHP formatting
- **PSR-12** coding standard
- **Pest PHP** for testing

## Credits

### Maintainer
- **Ajjitech** — [GitHub](https://github.com/ajjitech)

### Original Author
This package is a Bootstrap 5 port of [williamug/searchable-select](https://github.com/williamug/searchable-select), originally created by **William Asaba** — [GitHub](https://github.com/Williamug).

### Built With
- [Laravel](https://laravel.com) — The PHP Framework
- [Livewire](https://livewire.laravel.com) — Full-stack framework for Laravel
- [Alpine.js](https://alpinejs.dev) — Lightweight JavaScript framework
- [Bootstrap](https://getbootstrap.com) — CSS framework

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Links

- **[Packagist](https://packagist.org/packages/ajjitech/searchable-select)** — Composer package
- **[GitHub Repository](https://github.com/ajjitech/searchable-select)** — Source code
- **[Issue Tracker](https://github.com/ajjitech/searchable-select/issues)** — Report bugs or request features
- **[Changelog](CHANGELOG.md)** — Version history

---

<div align="center">

**Made with care for the Laravel community**

[Report Bug](https://github.com/ajjitech/searchable-select/issues) · [Request Feature](https://github.com/ajjitech/searchable-select/issues) · [Contribute](https://github.com/ajjitech/searchable-select/pulls)

</div>
