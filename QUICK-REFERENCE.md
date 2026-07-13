# Searchable Select — Quick Reference

## Installation

```bash
composer require williamug/searchable-select
```

## Basic Usage

```php
// Livewire component
public ?int $country_id = null;
public array $countries;

public function mount(): void
{
    $this->countries = Country::orderBy('name')->get()->toArray();
}
```

```blade
<x-searchable-select
    wire:model="country_id"
    :options="$countries"
    placeholder="Select a country"
/>
```

## All Props at a Glance

```blade
<x-searchable-select
    wire:model="field"                {{-- Livewire two-way binding --}}
    :options="$data"                  {{-- Array or Collection --}}
    option-value="id"                 {{-- Value field (default: 'id') --}}
    option-label="name"               {{-- Label field (default: 'name') --}}
    option-subtitle="description"     {{-- Optional second line per option --}}
    option-icon="avatar_url"          {{-- Optional image thumbnail per option --}}
    placeholder="Select..."           {{-- Trigger placeholder --}}
    search-placeholder="Search..."    {{-- Search input placeholder --}}
    empty-message="No results"        {{-- Shown when list is empty --}}
    :multiple="true"                  {{-- Multi-select with tags --}}
    :max-tags="3"                     {{-- Collapse excess tags to '+N more' --}}
    :clearable="true"                 {{-- Show × clear button --}}
    :disabled="false"                 {{-- Disable the dropdown --}}
    :searchable="true"                {{-- Show/hide the search input --}}
    max-height="240px"                {{-- Options list height (any CSS value) --}}
    :min-length="2"                   {{-- Min chars before filtering starts --}}
    placement="auto"                  {{-- 'auto' | 'top' | 'bottom' --}}
    :async="false"                    {{-- Server-side search mode --}}
    :grouped="false"                  {{-- Enable group headers --}}
    group-label="label"               {{-- Group header key --}}
    group-options="options"           {{-- Group items key --}}
/>
```

## Common Patterns

### 1. Simple Dropdown
```blade
<x-searchable-select wire:model="country_id" :options="$countries" />
```

### 2. Multi-Select with Tag Cap
```blade
<x-searchable-select
    wire:model="skill_ids"
    :options="$skills"
    :multiple="true"
    :max-tags="3"
    placeholder="Select skills"
/>
```

### 3. Cascading Dropdowns
```blade
<x-searchable-select wire:model="country_id" :options="$countries" />
<x-searchable-select
    wire:model="city_id"
    :options="$cities"
    :disabled="!$country_id"
    placeholder="First select a country"
/>
```

### 4. Option Subtitle + Icon
```blade
<x-searchable-select
    wire:model="user_id"
    :options="$users"
    option-subtitle="email"
    option-icon="avatar_url"
    placeholder="Select a user"
/>
```

### 5. Async / Server-Side Search
```php
use Livewire\Attributes\On;

#[On('searchable-select:search')]
public function search(string $query, ?string $key): void
{
    $this->countries = Country::where('name', 'like', "%{$query}%")
        ->limit(50)->get()->toArray();
}
```
```blade
<x-searchable-select
    wire:model="country_id"
    :options="$countries"
    :async="true"
    :min-length="2"
    placeholder="Type to search..."
/>
```

### 6. Grouped Options
```blade
<x-searchable-select
    wire:model="country_id"
    :options="$grouped"
    :grouped="true"
    group-label="label"
    group-options="options"
/>
```

### 7. No Search Input
```blade
<x-searchable-select
    wire:model="status"
    :options="$statuses"
    :searchable="false"
    placeholder="Select a status"
/>
```

### 8. Force Upward / Custom Height
```blade
<x-searchable-select
    wire:model="country_id"
    :options="$countries"
    placement="top"
    max-height="180px"
/>
```

### 9. Alpine x-model (No Livewire)
```blade
<div x-data="{ selectedId: null }">
    <x-searchable-select
        x-model="selectedId"
        :options="$countries"
        placeholder="Select a country"
    />
</div>
```

## Grouped Data Structure

```php
$options = [
    [
        'label'   => 'East Africa',
        'options' => [
            ['id' => 1, 'name' => 'Uganda'],
            ['id' => 2, 'name' => 'Kenya'],
        ],
    ],
    [
        'label'   => 'West Africa',
        'options' => [
            ['id' => 3, 'name' => 'Ghana'],
        ],
    ],
];
```

## Tailwind Setup

### v4
```css
/* resources/css/app.css */
@import 'tailwindcss';
@source '../../vendor/williamug/searchable-select/resources/views/**/*.blade.php';
```

### v3
```js
// tailwind.config.js
export default {
  content: [
    './resources/**/*.blade.php',
    './vendor/williamug/searchable-select/resources/views/**/*.blade.php',
  ],
}
```

## Validation

```php
protected $rules = ['country_id' => 'required|exists:countries,id'];
```

```blade
<x-searchable-select wire:model="country_id" :options="$countries" />
@error('country_id')
    <span class="text-red-500 text-sm">{{ $message }}</span>
@enderror
```

## Performance Guidelines

| Options count | Approach |
|---------------|----------|
| < 500         | Client-side (default) |
| 500 – 5 000   | `:async="true"` with `min-length` |
| 5 000+        | `:async="true"` + database full-text search |

## Useful Commands

```bash
# Publish views for customization
php artisan vendor:publish --tag=searchable-select-views

# Clear view cache
php artisan view:clear

# Run package tests
composer test
```

## Run the Demo

```bash
cd demo
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Visit `http://localhost:8000`

---

**[Full README](README.md)** · **[Changelog](CHANGELOG.md)** · **[Packagist](https://packagist.org/packages/williamug/searchable-select)**
