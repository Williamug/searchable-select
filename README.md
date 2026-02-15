# Livewire Searchable Select

[![Latest Version on Packagist](https://img.shields.io/packagist/v/williamug/searchable-select.svg?style=flat-square)](https://packagist.org/packages/williamug/searchable-select)
[![run-tests](https://github.com/Williamug/searchable-select/actions/workflows/run-tests.yml/badge.svg)](https://github.com/Williamug/searchable-select/actions/workflows/run-tests.yml)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/Williamug/searchable-select/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/Williamug/searchable-select/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/williamug/searchable-select.svg?style=flat-square)](https://packagist.org/packages/williamug/searchable-select)
[![License](https://img.shields.io/packagist/l/williamug/searchable-select.svg?style=flat-square)](https://packagist.org/packages/williamug/searchable-select)

A beautiful, searchable dropdown component for Laravel Livewire 3 & 4 applications. Built with Alpine.js and Tailwind CSS - no external dependencies required!

## Screenshots

<p align="center">
  <img src="images/Pasted%20image.png" width="45%" style="margin-right: 2%; border: 1px solid #ddd; border-radius: 4px; padding: 5px;" />
  <img src="images/Pasted%20image%20(2).png" width="45%" style="border: 1px solid #ddd; border-radius: 4px; padding: 5px;" />
</p>

## Features

- **Real-time search** - Client-side filtering as you type
- **Dark mode support** - Automatically adapts to your theme
- **Accessible** - Keyboard navigation and ARIA attributes
- **Livewire 3 & 4 compatible** - Works with both versions
- **Responsive** - Mobile-friendly design
- **Disabled state** - Conditional disabling support
- **Flexible data** - Works with models, arrays, collections
- **Dependent dropdowns** - Perfect for cascading selects
- **Customizable** - Override styles with Tailwind classes
- **Zero config** - Works out of the box
## Requirements

- PHP 8.1+
- Laravel 9.x, 10.x, 11.x, or 12.x
- Livewire 3.x or 4.x
- Tailwind CSS 3.x+
- Alpine.js (bundled with Livewire)

## Installation

Install via Composer:

```bash
composer require williamug/searchable-select
```

Run the installation command:

```bash
php artisan install:searchable-select
```

That's it! The component will be copied to `resources/views/components/searchable-select.blade.php`

### Force reinstall

If you want to overwrite an existing installation:

```bash
php artisan install:searchable-select --force
```

## Quick Start

### Basic Usage

**Livewire Component:**
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

    public function render()
    {
        return view('livewire.contact-form');
    }
}
```

**Blade View:**
```blade
<x-searchable-select
    :options="$countries"
    wire-model="country_id"
    :selected-value="$country_id"
    placeholder="Select Country"
    search-placeholder="Search countries..."
/>
```

### Dependent/Cascading Dropdowns

If you have related dropdowns (e.g. Country → Region → City), you can easily update the options based on the selected value of the parent dropdown.

**Livewire Component:**
```php
<?php

namespace App\Livewire;

use App\Models\{Country, Region, City};
use Livewire\Component;

class LocationSelector extends Component
{
    public $countries, $regions = [], $cities = [];
    public $country_id, $region_id, $city_id;

    public function mount()
    {
        $this->countries = Country::orderBy('name')->get();
    }

    public function updatedCountryId()
    {
        $this->regions = Region::where('country_id', $this->country_id)
            ->orderBy('name')->get();
        $this->region_id = null;
        $this->city_id = null;
        $this->cities = [];
    }

    public function updatedRegionId()
    {
        $this->cities = City::where('region_id', $this->region_id)
            ->orderBy('name')->get();
        $this->city_id = null;
    }

    public function render()
    {
        return view('livewire.location-selector');
    }
}
```

**Blade View:**
```blade
<div class="grid grid-cols-3 gap-4">
    <!-- Country -->
    <div>
        <label>Country</label>
        <x-searchable-select
            :options="$countries"
            wire-model="country_id"
            :selected-value="$country_id"
            placeholder="Select Country"
        />
    </div>

    <!-- Region -->
    <div>
        <label>Region</label>
        <x-searchable-select
            :options="$regions"
            wire-model="region_id"
            :selected-value="$region_id"
            :placeholder="empty($regions) ? 'First select a country' : 'Select Region'"
            :disabled="!$country_id"
        />
    </div>

    <!-- City -->
    <div>
        <label>City</label>
        <x-searchable-select
            :options="$cities"
            wire-model="city_id"
            :selected-value="$city_id"
            :placeholder="empty($cities) ? 'First select a region' : 'Select City'"
            :disabled="!$region_id"
        />
    </div>
</div>
```

## Component Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `options` | Array/Collection | `[]` | List of options to display |
| `wireModel` | String | `''` | Livewire property to bind (required) |
| `selectedValue` | Mixed | `null` | Currently selected value |
| `placeholder` | String | `'Select option'` | Placeholder when nothing selected |
| `searchPlaceholder` | String | `'Search...'` | Search input placeholder |
| `disabled` | Boolean | `false` | Disable the dropdown |
| `emptyMessage` | String | `'No options available'` | Message when options is empty |
| `optionValue` | String | `'id'` | Key for option values |
| `optionLabel` | String | `'name'` | Key for option labels |

## Advanced Examples

### With Arrays

```php
public $statuses = [
    ['id' => 'draft', 'name' => 'Draft'],
    ['id' => 'published', 'name' => 'Published'],
    ['id' => 'archived', 'name' => 'Archived'],
];
```

```blade
<x-searchable-select
    :options="$statuses"
    wire-model="status"
    :selected-value="$status"
/>
```

### Custom Keys

```php
public $products; // Has 'sku' and 'product_name' fields
```

```blade
<x-searchable-select
    :options="$products"
    wire-model="product_sku"
    :selected-value="$product_sku"
    option-value="sku"
    option-label="product_name"
/>
```

### Custom Styling

```blade
<x-searchable-select
    :options="$countries"
    wire-model="country_id"
    :selected-value="$country_id"
    class="border-2 border-blue-500 rounded-xl"
/>
```

### With Validation

```php
protected $rules = [
    'country_id' => 'required|exists:countries,id',
    'city_id' => 'required|exists:cities,id',
];
```

```blade
<x-searchable-select
    :options="$countries"
    wire-model="country_id"
    :selected-value="$country_id"
/>
@error('country_id')
    <span class="text-red-500 text-sm">{{ $message }}</span>
@enderror
```

## Customization

### Tailwind Configuration

Make sure your `tailwind.config.js` includes the component path:

```js
export default {
  content: [
    './resources/views/**/*.blade.php',
    './resources/views/components/**/*.blade.php',
  ],
}
```

### Create Specialized Components

Create a dedicated component for common use cases:

**resources/views/components/country-select.blade.php:**
```blade
<x-searchable-select
    :options="\App\Models\Country::orderBy('name')->get()"
    wire-model="{{ $wireModel }}"
    :selected-value="$selectedValue"
    placeholder="Select Country"
    search-placeholder="Search countries..."
    {{ $attributes }}
/>
```

**Usage:**
```blade
<x-country-select wire-model="country_id" :selected-value="$country_id" />
```

## Troubleshooting

### Dropdown doesn't open
- Verify Alpine.js is loaded (part of Livewire 3+)
- Check browser console for JavaScript errors
- Ensure no JavaScript conflicts

### Selected value not displaying
- Verify `selectedValue` matches an option value
- Check `optionValue` prop matches your data structure
- Ensure the value exists in options array

### Styling issues
- Run `npm run build` to compile Tailwind
- Verify component path in `tailwind.config.js`
- Check for CSS conflicts

## Performance

- **< 1,000 options**: Client-side filtering (default) works great
- **> 1,000 options**: Consider server-side search with `wire:model.live.debounce`
- **Very large datasets**: Implement pagination or lazy loading

## Testing

The package includes comprehensive tests. Run them with:

```bash
composer test
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Write tests for your changes
4. Ensure tests pass (`composer test`)
5. Commit your changes (`git commit -m 'Add amazing feature'`)
6. Push to the branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- [William Asaba](https://github.com/Williamug)
- Built with [Laravel](https://laravel.com)
- Powered by [Livewire](https://livewire.laravel.com)
- Styled with [Tailwind CSS](https://tailwindcss.com)
- Enhanced with [Alpine.js](https://alpinejs.dev)

## Links

- [Packagist](https://packagist.org/packages/williamug/searchable-select)
- [GitHub](https://github.com/williamug/searchable-select)
- [Issues](https://github.com/williamug/searchable-select/issues)

---

If this package helped you, please ⭐ star the repository!
