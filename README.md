# Livewire Searchable Select

[![Latest Version on Packagist](https://img.shields.io/packagist/v/williamug/searchable-select.svg?style=flat-square)](https://packagist.org/packages/williamug/searchable-select)
[![run-tests](https://github.com/Williamug/searchable-select/actions/workflows/run-tests.yml/badge.svg)](https://github.com/Williamug/searchable-select/actions/workflows/run-tests.yml)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/Williamug/searchable-select/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/Williamug/searchable-select/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/williamug/searchable-select.svg?style=flat-square)](https://packagist.org/packages/williamug/searchable-select)
[![License](https://img.shields.io/packagist/l/williamug/searchable-select.svg?style=flat-square)](https://packagist.org/packages/williamug/searchable-select)

A powerful, feature-rich searchable dropdown component for Laravel Livewire 3 & 4 applications. Built with Alpine.js and supports both **Tailwind CSS** and **Bootstrap 5** out of the box!

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [CSS Framework Setup](#css-framework-setup)
  - [Tailwind CSS (Default)](#tailwind-css-default)
  - [Bootstrap 5](#bootstrap-5)
  - [Switching Themes](#switching-themes)
- [Quick Start](#quick-start)
- [Component Props Reference](#component-props-reference)
- [Usage Examples](#usage-examples)
  - [Basic Single Select](#basic-single-select)
  - [Multi-Select](#multi-select)
  - [Dependent/Cascading Dropdowns](#dependentcascading-dropdowns)
  - [Grouped Options](#grouped-options)
  - [API/Ajax Integration](#apiajax-integration)
  - [Custom Keys](#custom-keys)
  - [With Validation](#with-validation)
  - [Disabled State](#disabled-state)
  - [Without Clear Button](#without-clear-button)
- [Advanced Features](#advanced-features)
- [Customization Guide](#customization-guide)
- [Troubleshooting](#troubleshooting)
- [Performance Optimization](#performance-optimization)
- [Testing](#testing)
- [Demo Application](#demo-application)
- [Contributing](#contributing)
- [License](#license)

## Features

- **Real-time search** - Client-side filtering as you type
- **Multi-select support** - Select multiple options with visual tags/badges
- **Ajax/API integration** - Fetch options dynamically from REST endpoints
- **Grouped options** - Organize options into labeled categories
- **Clear button** - Quickly clear selections
- **Dark mode support** - Automatically adapts to your theme (Tailwind)
- **Accessible** - Full keyboard navigation and ARIA attributes
- **Livewire 3 & 4 compatible** - Works seamlessly with both versions
- **Responsive** - Mobile-friendly and touch-optimized
- **Disabled state** - Conditional disabling support
- **Flexible data** - Works with Eloquent models, arrays, collections
- **Dependent dropdowns** - Perfect for cascading country → region → city selects
- **Multiple CSS frameworks** - Full support for Tailwind CSS and Bootstrap 5
- **Per-component theme override** - Mix frameworks in the same app
- **Customizable** - Override styles and behavior
- **Zero config** - Works immediately after installation

## Screenshots

<p align="center">
  <img src="images/Pasted%20image.png" width="45%" style="margin-right: 2%; border: 1px solid #ddd; border-radius: 4px; padding: 5px;" />
  <img src="images/Pasted%20image%20(2).png" width="45%" style="border: 1px solid #ddd; border-radius: 4px; padding: 5px;" />
</p>

## Requirements

- **PHP**: 8.1 or higher
- **Laravel**: 9.x, 10.x, 11.x, or 12.x
- **Livewire**: 3.x or 4.x
- **Alpine.js**: Bundled with Livewire (no separate install needed)
- **CSS Framework** (choose one):
  - Tailwind CSS 3.x+ (default)
  - Bootstrap 5.x+

## Installation

Install the package via Composer:

```bash
composer require williamug/searchable-select
```

The package will automatically register its service provider. You're ready to use it immediately!

You can publish the configuration file:

```bash
php artisan vendor:publish --tag=searchable-select-config
```

## CSS Framework Setup

### Tailwind CSS (Default)

The component uses **Tailwind CSS** by default. No additional configuration needed!

**1. Ensure Tailwind is installed in your project:**

```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init
```

**2. Add the package views to your `tailwind.config.js`:**

```js
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './vendor/williamug/searchable-select/resources/views/**/*.blade.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
```

**3. Build your CSS:**

```bash
npm run build
```

That's it! The component will use Tailwind classes and support dark mode automatically.

### Bootstrap 5

To use **Bootstrap 5** instead of Tailwind:

**1. Publish the configuration file:**

```bash
php artisan vendor:publish --tag=searchable-select-config
```

This creates `config/searchable-select.php`.

**2. Set the theme to Bootstrap:**

Edit `config/searchable-select.php`:

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    |
    | The default CSS framework to use for rendering components.
    | Supported values: 'tailwind', 'bootstrap'
    |
    */
    'theme' => 'bootstrap', // Change from 'tailwind' to 'bootstrap'

    /*
    |--------------------------------------------------------------------------
    | Bootstrap Version
    |--------------------------------------------------------------------------
    |
    | The Bootstrap version to target for styling.
    | Currently supported: '5.3'
    |
    */
    'bootstrap_version' => '5.3',
];
```

**Or use environment variable** (recommended):

Add to your `.env` file:

```env
SEARCHABLE_SELECT_THEME=bootstrap
```

**3. Include Bootstrap assets in your layout if you haven't already:**

Add to your `resources/views/layouts/app.blade.php` (or wherever your layout is):

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My App</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @livewireStyles
</head>
<body>
    @yield('content')

    <!-- Bootstrap Bundle with Popper (optional, for dropdowns/tooltips) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @livewireScripts
</body>
</html>
```

**Note**: The component's dropdown functionality uses Alpine.js (included with Livewire), so Bootstrap's JavaScript is optional unless you're using other Bootstrap components.

### Switching Themes

#### Global Theme Switch

Change the theme application-wide by updating `config/searchable-select.php`:

```php
'theme' => 'tailwind', // or 'bootstrap'
```

Or via environment variable:

```env
# Use Tailwind globally
SEARCHABLE_SELECT_THEME=tailwind

# Use Bootstrap globally
SEARCHABLE_SELECT_THEME=bootstrap
```

#### Per-Component Theme Override

You can override the theme for **individual components** using the `theme` prop:

```blade
{{-- This component uses Bootstrap --}}
<x-searchable-select
    theme="bootstrap"
    :options="$countries"
    wire-model="country_id"
/>

{{-- This component uses Tailwind --}}
<x-searchable-select
    theme="tailwind"
    :options="$cities"
    wire-model="city_id"
/>

{{-- This uses the global config theme --}}
<x-searchable-select
    :options="$regions"
    wire-model="region_id"
/>
```

This allows you to:
- Gradually migrate from one framework to another
- Mix frameworks in the same application
- Use different themes for different sections of your app


## Quick Start

### Basic Usage

**Step 1: Create a Livewire Component**

```bash
php artisan make:livewire ContactForm
```

**Step 2: Set up your component class**

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
        // Load all countries
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
    <label for="country" class="block mb-2">Country</label>

    <x-searchable-select
        :options="$countries"
        wire-model="country_id"
        :selected-value="$country_id"
        placeholder="Select a country"
        search-placeholder="Type to search countries..."
    />

    @error('country_id')
        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
    @enderror

    <button wire:click="save" class="mt-4">Save</button>
</div>
```

That's it! You now have a fully functional searchable dropdown.

## Component Props Reference

Comprehensive list of all available props:

| Prop | Type | Default | Required | Description |
|------|------|---------|----------|-------------|
| `options` | Array/Collection | `[]` | Yes | The list of options to display in the dropdown |
| `wireModel` | String | `''` | Yes | The Livewire property to bind the selected value to |
| `selectedValue` | Mixed | `null` | No | The currently selected value (for reactivity) |
| `placeholder` | String | `'Select option'` | No | Placeholder text shown when nothing is selected |
| `searchPlaceholder` | String | `'Search...'` | No | Placeholder for the search input field |
| `disabled` | Boolean | `false` | No | Whether the dropdown is disabled |
| `emptyMessage` | String | `'No options available'` | No | Message shown when the options array is empty |
| `optionValue` | String | `'id'` | No | The key/property to use as the option value |
| `optionLabel` | String | `'name'` | No | The key/property to use as the option display label |
| `multiple` | Boolean | `false` | No | Enable multi-select mode (allows selecting multiple options) |
| `clearable` | Boolean | `true` | No | Show/hide the clear button |
| `apiUrl` | String | `null` | No | API endpoint URL for fetching options dynamically |
| `apiSearchParam` | String | `'search'` | No | Query parameter name for API search (e.g., `?search=term`) |
| `grouped` | Boolean | `false` | No | Enable grouped/categorized options mode |
| `groupLabel` | String | `'label'` | No | Key for group labels (when `grouped` is true) |
| `groupOptions` | String | `'options'` | No | Key for group options array (when `grouped` is true) |
| `theme` | String | `null` | No | Override the CSS framework theme (`'tailwind'` or `'bootstrap'`) |

### Props Explanation

#### Core Props

- **`options`**: The data source for your dropdown. Can be:
  - Eloquent Collection: `Country::all()`
  - Array of objects: `[['id' => 1, 'name' => 'USA'], ...]`
  - Array of arrays: See above

- **`wireModel`**: The Livewire property name to bind. Use `wire-model` (kebab-case) in Blade.

- **`selectedValue`**: Pass the current value to keep the component in sync. Essential for reactive updates.

#### Labeling Props

- **`placeholder`**: Shows when no option is selected
- **`searchPlaceholder`**: Shows in the search input
- **`emptyMessage`**: Shows when `options` array is empty

#### Data Mapping Props

- **`optionValue`**: Which property to use as the value (saved to `wire-model`)
- **`optionLabel`**: Which property to display to users

Example:
```php
// If your model has 'code' and 'country_name' fields
$countries = Country::all(); // [['code' => 'US', 'country_name' => 'United States'], ...]
```

```blade
<x-searchable-select
    :options="$countries"
    option-value="code"
    option-label="country_name"
    wire-model="country_code"
/>
```

#### Feature Flags

- **`multiple`**: Enables multi-select mode with visual tags
- **`clearable`**: Shows/hides the × button to clear selection
- **`disabled`**: Grays out the component and prevents interaction
- **`grouped`**: Enables category headers in the dropdown

#### API Integration Props

- **`apiUrl`**: Backend endpoint that returns JSON with options
- **`apiSearchParam`**: The query parameter for search term

See [API/Ajax Integration](#apiajax-integration) for full examples.

#### Theme Props

- **`theme`**: Override the global theme setting for this specific component


## Usage Examples

### Basic Single Select

The most common use case - a simple searchable dropdown:

```php
<?php

namespace App\Livewire;

use App\Models\Country;
use Livewire\Component;

class UserProfile extends Component
{
    public $countries;
    public $country_id;

    public function mount()
    {
        $this->countries = Country::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.user-profile');
    }
}
```

```blade
<x-searchable-select
    :options="$countries"
    wire-model="country_id"
    :selected-value="$country_id"
    placeholder="Select your country"
    search-placeholder="Search countries..."
/>
```

### Multi-Select

Select multiple options with visual tags/badges:

```php
<?php

namespace App\Livewire;

use App\Models\Skill;
use Livewire\Component;

class UserSkills extends Component
{
    public $skills;
    public $selected_skills = []; // Array to hold multiple selections

    public function mount()
    {
        $this->skills = Skill::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.user-skills');
    }
}
```

```blade
<x-searchable-select
    :options="$skills"
    wire-model="selected_skills"
    :selected-value="$selected_skills"
    :multiple="true"
    placeholder="Select your skills"
    search-placeholder="Search skills..."
/>

{{-- Display selected skills --}}
@if(!empty($selected_skills))
    <div class="mt-2">
        <p>Selected: {{ count($selected_skills) }} skills</p>
    </div>
@endif
```

**Tailwind styling**: Selected items show as blue badges with × remove buttons
**Bootstrap styling**: Selected items show as primary badges with × remove buttons



### Dependent/Cascading Dropdowns

Create related dropdowns where child options depend on parent selections (e.g., Country → Region → City):

```php
<?php

namespace App\Livewire;

use App\Models\{Country, Region, City};
use Livewire\Component;

class LocationSelector extends Component
{
    // Options
    public $countries;
    public $regions = [];
    public $cities = [];

    // Selected values
    public $country_id;
    public $region_id;
    public $city_id;

    public function mount()
    {
        // Load countries on page load
        $this->countries = Country::orderBy('name')->get();
    }

    public function updatedCountryId($value)
    {
        // When country changes, load its regions
        $this->regions = Region::where('country_id', $value)
            ->orderBy('name')
            ->get();

        // Reset child selections
        $this->region_id = null;
        $this->city_id = null;
        $this->cities = [];
    }

    public function updatedRegionId($value)
    {
        // When region changes, load its cities
        $this->cities = City::where('region_id', $value)
            ->orderBy('name')
            ->get();

        // Reset city selection
        $this->city_id = null;
    }

    public function render()
    {
        return view('livewire.location-selector');
    }
}
```

```blade
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <!-- Country Dropdown -->
    <div>
        <label class="block mb-2 font-medium">Country</label>
        <x-searchable-select
            :options="$countries"
            wire-model.live="country_id"
            :selected-value="$country_id"
            placeholder="Select Country"
            search-placeholder="Search countries..."
        />
    </div>

    <!-- Region Dropdown (disabled until country is selected) -->
    <div>
        <label class="block mb-2 font-medium">Region</label>
        <x-searchable-select
            :options="$regions"
            wire-model.live="region_id"
            :selected-value="$region_id"
            :placeholder="empty($regions) ? 'First select a country' : 'Select Region'"
            :disabled="!$country_id"
        />
    </div>

    <!-- City Dropdown (disabled until region is selected) -->
    <div>
        <label class="block mb-2 font-medium">City</label>
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

**Key points:**
- Use `wire-model.live` on parent dropdowns to trigger updates immediately
- Use `updatedPropertyName()` methods to react to changes
- Reset child values when parent changes
- Use `:disabled` prop to prevent selecting child before parent

### Grouped Options

Organize options into labeled categories:

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class CountrySelector extends Component
{
    public $country_id;

    public $locations = [
        [
            'label' => 'North America',
            'options' => [
                ['id' => 1, 'name' => 'United States'],
                ['id' => 2, 'name' => 'Canada'],
                ['id' => 3, 'name' => 'Mexico'],
            ]
        ],
        [
            'label' => 'Europe',
            'options' => [
                ['id' => 4, 'name' => 'United Kingdom'],
                ['id' => 5, 'name' => 'France'],
                ['id' => 6, 'name' => 'Germany'],
                ['id' => 7, 'name' => 'Spain'],
                ['id' => 8, 'name' => 'Italy'],
            ]
        ],
        [
            'label' => 'Asia',
            'options' => [
                ['id' => 9, 'name' => 'Japan'],
                ['id' => 10, 'name' => 'China'],
                ['id' => 11, 'name' => 'India'],
                ['id' => 12, 'name' => 'South Korea'],
            ]
        ],
    ];

    public function render()
    {
        return view('livewire.country-selector');
    }
}
```

```blade
<x-searchable-select
    :options="$locations"
    wire-model="country_id"
    :selected-value="$country_id"
    :grouped="true"
    placeholder="Select a country"
    search-placeholder="Search countries..."
/>
```

**Custom group keys:**

If your data structure uses different keys:

```php
public $categories = [
    [
        'category_name' => 'Fruits',      // Custom group label key
        'items' => [                       // Custom options key
            ['code' => 'APL', 'title' => 'Apple'],
            ['code' => 'BAN', 'title' => 'Banana'],
        ]
    ],
];
```

```blade
<x-searchable-select
    :options="$categories"
    :grouped="true"
    group-label="category_name"
    group-options="items"
    option-value="code"
    option-label="title"
    wire-model="selected_item"
/>
```

### API/Ajax Integration

Fetch options dynamically from a backend API:

**Step 1: Create an API endpoint**

```php
// routes/api.php
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/users/search', function (Request $request) {
    $query = User::query();

    // Search by the query parameter
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
    }

    $users = $query->select('id', 'name')
                   ->limit(50)
                   ->get();

    return response()->json([
        'data' => $users
    ]);
});
```

**Step 2: Use the component with API URL**

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class AssignTask extends Component
{
    public $assigned_user_id;

    public function render()
    {
        return view('livewire.assign-task');
    }
}
```

```blade
<x-searchable-select
    :options="[]"
    wire-model="assigned_user_id"
    :selected-value="$assigned_user_id"
    api-url="{{ route('api.users.search') }}"
    api-search-param="search"
    placeholder="Search for a user..."
    search-placeholder="Type to search users..."
/>
```

**Multi-select with API:**

```php
public $team_members = []; // Array for multiple selections
```

```blade
<x-searchable-select
    :options="[]"
    wire-model="team_members"
    :selected-value="$team_members"
    :multiple="true"
    api-url="{{ route('api.users.search') }}"
    placeholder="Add team members"
/>
```

**API Response Format:**

Your API should return JSON in this format:

```json
{
    "data": [
        {"id": 1, "name": "John Doe"},
        {"id": 2, "name": "Jane Smith"},
        {"id": 3, "name": "Bob Johnson"}
    ]
}
```

For custom keys, use `option-value` and `option-label`:

```json
{
    "data": [
        {"user_id": 1, "full_name": "John Doe"},
        {"user_id": 2, "full_name": "Jane Smith"}
    ]
}
```

```blade
<x-searchable-select
    api-url="/api/users"
    option-value="user_id"
    option-label="full_name"
    wire-model="user_id"
/>
```

### Custom Keys

When your data uses different property names:

```php
public $products = [
    ['sku' => 'PROD-001', 'product_name' => 'Laptop'],
    ['sku' => 'PROD-002', 'product_name' => 'Mouse'],
    ['sku' => 'PROD-003', 'product_name' => 'Keyboard'],
];

public $selected_sku;
```

```blade
<x-searchable-select
    :options="$products"
    wire-model="selected_sku"
    :selected-value="$selected_sku"
    option-value="sku"
    option-label="product_name"
    placeholder="Select a product"
/>
```

### With Validation

Integrate with Laravel's validation:

```php
<?php

namespace App\Livewire;

use App\Models\Country;
use Livewire\Component;

class ContactForm extends Component
{
    public $countries;
    public $country_id;
    public $city_id;

    protected $rules = [
        'country_id' => 'required|exists:countries,id',
        'city_id' => 'required|exists:cities,id',
    ];

    protected $messages = [
        'country_id.required' => 'Please select a country.',
        'city_id.required' => 'Please select a city.',
    ];

    public function mount()
    {
        $this->countries = Country::all();
    }

    public function save()
    {
        $validated = $this->validate();

        // Use validated data...
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
```

```blade
<div>
    <label>Country *</label>
    <x-searchable-select
        :options="$countries"
        wire-model="country_id"
        :selected-value="$country_id"
    />
    @error('country_id')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<div class="mt-4">
    <label>City *</label>
    <x-searchable-select
        :options="$cities"
        wire-model="city_id"
        :selected-value="$city_id"
    />
    @error('city_id')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<button wire:click="save" class="mt-4">Save</button>
```

**Real-time validation:**

```php
public function updated($propertyName)
{
    $this->validateOnly($propertyName);
}
```

### Disabled State

Conditionally disable the dropdown:

```blade
<x-searchable-select
    :options="$regions"
    wire-model="region_id"
    :selected-value="$region_id"
    :disabled="!$country_id"
    placeholder="First select a country"
/>
```

### Without Clear Button

Hide the clear (×) button:

```blade
<x-searchable-select
    :options="$countries"
    wire-model="country_id"
    :selected-value="$country_id"
    :clearable="false"
/>
```

### Using Arrays Instead of Models

You don't need Eloquent models - plain arrays work too:

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

## Advanced Features

### Custom Styling with CSS Classes

Add custom classes to the component wrapper:

**Tailwind:**
```blade
<x-searchable-select
    :options="$countries"
    wire-model="country_id"
    class="border-2 border-blue-500 rounded-xl shadow-lg"
/>
```

**Bootstrap:**
```blade
<x-searchable-select
    theme="bootstrap"
    :options="$countries"
    wire-model="country_id"
    class="shadow-sm border-primary"
/>
```

### Creating Specialized Components

Build reusable components for common patterns:

**resources/views/components/country-select.blade.php:**
```blade
@props(['wireModel', 'selectedValue' => null])

<x-searchable-select
    :options="\App\Models\Country::orderBy('name')->get()"
    wire-model="{{ $wireModel }}"
    :selected-value="$selectedValue"
    placeholder="Select a country"
    search-placeholder="Search countries..."
    {{ $attributes }}
/>
```

**Usage:**
```blade
<x-country-select wire-model="country_id" :selected-value="$country_id" />
```

### Server-Side Search (Large Datasets)

For thousands of records, implement server-side search:

```php
public $searchTerm = '';
public $countries = [];

public function updatedSearchTerm($value)
{
    $this->countries = Country::where('name', 'like', "%{$value}%")
        ->limit(50)
        ->get();
}
```

Or better yet, use the built-in API integration feature shown above.

### Mixing Themes in One Application

You can use both Tailwind and Bootstrap in the same app:

```blade
{{-- Admin panel uses Tailwind --}}
<x-searchable-select
    theme="tailwind"
    :options="$users"
    wire-model="admin_id"
/>

{{-- Public form uses Bootstrap --}}
<x-searchable-select
    theme="bootstrap"
    :options="$countries"
    wire-model="country_id"
/>
```

## Customization Guide

### Publishing Views

If you need to customize the component HTML:

```bash
php artisan vendor:publish --tag=searchable-select-views
```

This copies the views to `resources/views/vendor/searchable-select/`:
- `searchable-select.blade.php` - Tailwind version
- `searchable-select-bootstrap.blade.php` - Bootstrap version

Now you can modify them as needed. Your custom views will be used instead of the package defaults.

### Dark Mode Support (Tailwind)

The Tailwind version automatically supports dark mode:

```html
<html class="dark">
    <!-- Component automatically uses dark:bg-gray-800, dark:text-white, etc. -->
</html>
```

### Bootstrap Dark Mode

For Bootstrap dark mode, you can:

1. Use Bootstrap's `data-bs-theme` attribute:
```html
<html data-bs-theme="dark">
```

2. Or publish the Bootstrap view and customize the color classes

### Customizing Search Behavior

The component uses client-side filtering by default. To customize:

1. **Case sensitivity**: Modify the Alpine.js `searchTerm` filtering logic
2. **Search multiple fields**: Adjust the filter to check multiple properties
3. **Server-side search**: Use `wire-model.live.debounce` with API integration

## Troubleshooting

### Common Issues and Solutions

#### Dropdown doesn't open / Click doesn't work

**Causes:**
- Alpine.js not loaded
- JavaScript conflicts
- Multiple Alpine.js instances

**Solutions:**
1. Verify Alpine.js is loaded (it comes with Livewire 3+):
```blade
@livewireScripts {{-- This includes Alpine.js --}}
```

2. Check browser console for JavaScript errors (F12 → Console)

3. Ensure you're not loading Alpine.js separately if using Livewire 3+:
```html
<!-- ❌ Remove this if you have Livewire 3+ -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

4. Try clearing browser cache and hard refresh (Ctrl+Shift+R / Cmd+Shift+R)

#### Selected value not displaying

**Causes:**
- Value mismatch between `selectedValue` and options
- Wrong `optionValue` key
- Value not in options array

**Solutions:**
1. Verify the selected value exists in your options:
```php
// ✅ Correct
$this->country_id = 1;
$this->countries = Country::all(); // Contains id=1

// ❌ Incorrect
$this->country_id = 999; // ID doesn't exist in countries
```

2. Check `optionValue` matches your data structure:
```php
// If your data uses 'code' instead of 'id'
$countries = [['code' => 'US', 'name' => 'USA']];
```
```blade
<x-searchable-select
    :options="$countries"
    option-value="code"  {{-- Must specify 'code' --}}
    wire-model="country_code"
/>
```

3. Use browser DevTools to inspect the component's Alpine.js data

#### Styling issues (Tailwind)

**Causes:**
- Package views not included in Tailwind purge paths
- Tailwind not built
- CSS not loading

**Solutions:**
1. Add package views to `tailwind.config.js`:
```js
export default {
  content: [
    './resources/**/*.blade.php',
    './vendor/williamug/searchable-select/resources/views/**/*.blade.php', // Add this
  ],
}
```

2. Rebuild Tailwind CSS:
```bash
npm run build
# or for development
npm run dev
```

3. Clear Laravel view cache:
```bash
php artisan view:clear
```

4. Check that your CSS is loading in browser DevTools (Network tab)

#### Styling issues (Bootstrap)

**Causes:**
- Bootstrap CSS not loaded
- Wrong theme configuration
- CSS conflicts

**Solutions:**
1. Verify Bootstrap is loaded in your layout:
```html
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
```

2. Confirm theme is set to Bootstrap:
```bash
# Check config
php artisan tinker
>>> config('searchable-select.theme');
=> "bootstrap"
```

3. Clear config cache:
```bash
php artisan config:clear
php artisan view:clear
```

4. Use browser DevTools to inspect if correct classes are applied (should see `form-control`, `dropdown-menu`, etc.)

#### Options not updating / Stale data

**Causes:**
- Missing `wire:key` on components
- Not using `selectedValue` prop
- Livewire not detecting changes

**Solutions:**
1. Always pass `:selected-value` for reactivity:
```blade
{{-- ✅ Correct --}}
<x-searchable-select
    :options="$countries"
    wire-model="country_id"
    :selected-value="$country_id"  {{-- Important! --}}
/>

{{-- ❌ Incorrect --}}
<x-searchable-select
    :options="$countries"
    wire-model="country_id"
    {{-- Missing :selected-value --}}
/>
```

2. Use `wire:key` when rendering multiple components in loops:
```blade
@foreach($forms as $form)
    <x-searchable-select
        wire:key="country-{{ $form->id }}"
        :options="$countries"
        wire-model="forms.{{ $loop->index }}.country_id"
    />
@endforeach
```

3. Use `wire-model.live` for immediate updates:
```blade
<x-searchable-select
    wire-model.live="country_id"  {{-- Updates immediately --}}
/>
```

#### API integration not working

**Causes:**
- Wrong API URL
- CORS issues
- Wrong response format

**Solutions:**
1. Verify API endpoint is accessible:
```bash
curl http://your-app.test/api/users/search?search=john
```

2. Check API response format (must have `data` key):
```json
{
    "data": [
        {"id": 1, "name": "John"}
    ]
}
```

3. Check browser Network tab (F12) for API requests and responses

4. For CORS issues, add to `config/cors.php`:
```php
'paths' => ['api/*'],
'allowed_origins' => ['*'],
```

#### Multi-select not working

**Causes:**
- Property not defined as array
- Missing `:multiple="true"`

**Solutions:**
1. Initialize property as array:
```php
// ✅ Correct
public $selected_items = [];

// ❌ Incorrect
public $selected_items; // null, not an array
```

2. Enable multiple mode:
```blade
<x-searchable-select
    :multiple="true"  {{-- Required for multi-select --}}
    wire-model="selected_items"
/>
```

#### Validation errors not showing

**Causes:**
- Missing `@error` directive
- Wrong property name in validation

**Solutions:**
1. Add error display:
```blade
<x-searchable-select wire-model="country_id" />
@error('country_id')
    <span class="text-red-500 text-sm">{{ $message }}</span>
@enderror
```

2. Verify property name matches:
```php
// Component
public $country_id; // Property name

protected $rules = [
    'country_id' => 'required', // Must match property name
];
```

#### Performance issues with large datasets

**Causes:**
- Too many options loaded at once
- Client-side filtering thousands of items

**Solutions:**
1. Use API integration for large datasets:
```blade
<x-searchable-select
    api-url="/api/search"  {{-- Fetch on-demand --}}
    :options="[]"          {{-- Don't load all upfront --}}
/>
```

2. Implement server-side pagination:
```php
Route::get('/api/search', function (Request $request) {
    return User::where('name', 'like', "%{$request->search}%")
        ->limit(50)  // Limit results
        ->get();
});
```

3. Use debouncing for search:
```blade
<x-searchable-select
    wire-model.live.debounce.500ms="search"  {{-- Wait 500ms before searching --}}
/>
```

## Performance Optimization

### Dataset Size Guidelines

| Options Count | Recommended Approach |
|--------------|---------------------|
| < 100 | Client-side filtering (default) - works perfectly |
| 100 - 1,000 | Client-side filtering with `wire:key` - still performant |
| 1,000 - 10,000 | Consider API integration with search - better UX |
| > 10,000 | **Must use API integration** - client-side will be slow |

### Optimization Techniques

**1. Lazy Loading with API:**
```blade
{{-- Don't load thousands of options upfront --}}
<x-searchable-select
    api-url="/api/products/search"
    :options="[]"
    placeholder="Search from 50,000 products..."
/>
```

**2. Server-Side Search:**
```php
// Livewire Component
public $searchTerm = '';
public $products = [];

public function updatedSearchTerm($value)
{
    $this->products = Product::where('name', 'like', "%{$value}%")
        ->limit(50)
        ->get();
}
```

**3. Caching Options:**
```php
public function mount()
{
    $this->countries = Cache::remember('countries', 3600, function () {
        return Country::orderBy('name')->get();
    });
}
```

**4. Select Only Needed Columns:**
```php
// ❌ Bad - loads all columns
$this->users = User::all();

// ✅ Good - only id and name
$this->users = User::select('id', 'name')->get();
```

**5. Debouncing for Dependent Dropdowns:**
```blade
<x-searchable-select
    wire-model.live.debounce.300ms="country_id"  {{-- Debounce API calls --}}
/>
```

## Testing

The package includes a comprehensive test suite covering all features.

### Running Tests

```bash
# Run all tests
composer test

# Run with coverage
composer test -- --coverage

# Run specific test file
./vendor/bin/pest tests/Feature/ComponentTest.php

# Run tests in parallel
./vendor/bin/pest --parallel
```

### Test Coverage

The package tests include:
- Component rendering (Tailwind & Bootstrap)
- Single-select functionality
- Multi-select with badges/tags
- Grouped options rendering
- Theme switching and overrides
- Service provider registration
- Configuration loading
- Install command

**24 tests, 46 assertions** - all passing

## Demo Application

The package includes a full-featured demo application showcasing all features.

### Running the Demo

**With Docker:**
```bash
cd demo
docker-compose up -d
```
Visit `http://localhost:8000`

**Without Docker:**
```bash
cd demo
composer install
php artisan serve
```

### Demo Features

The demo includes live examples of:
- **Basic single-select** - `/`
- **Multi-select mode** - `/multi-select`
- **Grouped options** - `/grouped`
- **API integration** - `/api-demo`
- **Dependent dropdowns** - `/cascading`
- **Bootstrap theme** - `/bootstrap`
- **All features combined** - `/advanced`

### Demo Source Code

Check the demo Livewire components in `demo/app/Livewire/` for implementation examples.



## Frequently Asked Questions

### Can I use both Tailwind and Bootstrap in the same project?

Yes! You can set different themes per component:
```blade
<x-searchable-select theme="tailwind" :options="$data1" wire-model="field1" />
<x-searchable-select theme="bootstrap" :options="$data2" wire-model="field2" />
```

### How do I implement country → state → city dropdowns?

See the [Dependent/Cascading Dropdowns](#dependentcascading-dropdowns) section for a complete example.

### Can I customize the component HTML?

Yes! Publish the views:
```bash
php artisan vendor:publish --tag=searchable-select-views
```
Then edit the files in `resources/views/vendor/searchable-select/`.

### Does it work with Livewire 3 and 4?

Yes, fully compatible with both Livewire 3.x and 4.x.

### How do I search across multiple fields?

Use API integration with a custom endpoint that searches multiple columns:
```php
Route::get('/api/search', function (Request $request) {
    return User::where('name', 'like', "%{$request->search}%")
        ->orWhere('email', 'like', "%{$request->search}%")
        ->orWhere('phone', 'like', "%{$request->search}%")
        ->get();
});
```

### Can I pre-select multiple values?

Yes, initialize your property as an array:
```php
public $selected_items = [1, 3, 5]; // Pre-selected IDs
```

### Does it support dark mode?

Yes, the Tailwind version automatically supports dark mode using `dark:` classes. For Bootstrap, use Bootstrap 5.3's dark mode features.

### How do I disable specific options?

This feature is not built-in, but you can publish the view and add a `disabled` property check in the options loop.

### Can I use it with Inertia.js?

The component is designed for Livewire. For Inertia.js, consider using a Vue/React select component instead.

### How do I add icons to options?

Publish the view and customize the option rendering to include icons:
```blade
<div>
    <img src="{{ $option->flag }}" class="w-4 h-4 inline mr-2">
    {{ $option->name }}
</div>
```

## Contributing

We welcome contributions! Here's how to get started:

### Development Setup

1. **Fork the repository**
   ```bash
   git clone https://github.com/YOUR-USERNAME/searchable-select.git
   cd searchable-select
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Run tests**
   ```bash
   composer test
   ```

### Contribution Workflow

1. **Create a feature branch**
   ```bash
   git checkout -b feature/amazing-feature
   ```

2. **Make your changes**
   - Add tests for new features
   - Update documentation if needed
   - Follow PSR-12 coding standards

3. **Run tests and code style checks**
   ```bash
   composer test
   composer format  # Fix code style
   ```

4. **Commit your changes**
   ```bash
   git commit -m 'Add amazing feature'
   ```

5. **Push to your fork**
   ```bash
   git push origin feature/amazing-feature
   ```

6. **Open a Pull Request**
   - Describe what your PR does
   - Reference any related issues
   - Ensure all tests pass

### Code Style

The project uses:
- **Laravel Pint** for PHP code formatting
- **PSR-12** coding standard
- **Pest PHP** for testing

Run before committing:
```bash
composer format    # Fix code style
composer test      # Run test suite
```

### Reporting Bugs

Found a bug? Please [open an issue](https://github.com/williamug/searchable-select/issues/new) with:
- Laravel version
- Livewire version
- PHP version
- CSS framework (Tailwind/Bootstrap)
- Steps to reproduce
- Expected vs actual behavior

### Suggesting Features

Have an idea? [Open a feature request](https://github.com/williamug/searchable-select/issues/new) describing:
- The use case
- How it would work
- Why it's useful
- Any implementation ideas

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for recent changes.

## Security

If you discover any security vulnerabilities, please email the maintainer instead of using the issue tracker.

## Credits

### Author
- **William Asaba** - [GitHub](https://github.com/Williamug) | [Twitter](https://twitter.com/williamasaba)

### Built With
- [Laravel](https://laravel.com) - The PHP Framework
- [Livewire](https://livewire.laravel.com) - A full-stack framework for Laravel
- [Alpine.js](https://alpinejs.dev) - Your new, lightweight, JavaScript framework
- [Tailwind CSS](https://tailwindcss.com) - A utility-first CSS framework
- [Bootstrap](https://getbootstrap.com) - The most popular HTML, CSS, and JS library

### Inspiration
Inspired by the need for a simple, framework-agnostic searchable select component for Laravel Livewire applications.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Support the Project

If this package saved you time and effort:
- ⭐ **Star the repository** on GitHub
- 🐦 **Share it** on social media
- 🤝 **Contribute** code or documentation
- 🐛 **Report bugs** to help improve it
- 💡 **Suggest features** you'd like to see

Your support helps maintain and improve this package!

## Links

- **[Packagist](https://packagist.org/packages/williamug/searchable-select)** - Composer package
- **[GitHub Repository](https://github.com/williamug/searchable-select)** - Source code
- **[Issue Tracker](https://github.com/williamug/searchable-select/issues)** - Report bugs or request features
- **[Changelog](https://github.com/williamug/searchable-select/blob/main/CHANGELOG.md)** - Version history
- **[License](https://github.com/williamug/searchable-select/blob/main/LICENSE.md)** - MIT License

---

<div align="center">

**Made with ❤️ for the Laravel community**

If this package helped you, please ⭐ star the repository!

[Report Bug](https://github.com/williamug/searchable-select/issues) · [Request Feature](https://github.com/williamug/searchable-select/issues) · [Contribute](https://github.com/williamug/searchable-select/pulls)

</div>
