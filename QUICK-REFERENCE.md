# 🚀 Searchable Select - Quick Reference Card

## Installation (30 seconds)

```bash
composer require williamug/searchable-select
```

That's it! Ready to use with Tailwind CSS.

## Basic Usage (Copy & Paste)

### Livewire Component

```php
<?php
namespace App\Livewire;

use App\Models\Country;
use Livewire\Component;

class MyForm extends Component
{
    public $countries;
    public $country_id;

    public function mount()
    {
        $this->countries = Country::all();
    }

    public function render()
    {
        return view('livewire.my-form');
    }
}
```

### Blade View

```blade
<x-searchable-select
    :options="$countries"
    wire-model="country_id"
    :selected-value="$country_id"
    placeholder="Select a country"
/>
```

## Switch to Bootstrap (2 minutes)

```bash
# 1. Publish config
php artisan vendor:publish --tag=searchable-select-config

# 2. Edit .env
echo "SEARCHABLE_SELECT_THEME=bootstrap" >> .env

# 3. Clear cache
php artisan config:clear
```

Add to layout:
```html
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
```

## Common Props

```blade
<x-searchable-select
    :options="$data"                           {{-- Array/Collection --}}
    wire-model="field"                         {{-- Required --}}
    :selected-value="$field"                   {{-- For reactivity --}}
    placeholder="Select..."                    {{-- Default text --}}
    search-placeholder="Search..."             {{-- Search input text --}}
    :multiple="true"                           {{-- Multi-select --}}
    :disabled="false"                          {{-- Disable dropdown --}}
    :clearable="true"                          {{-- Show clear button --}}
    option-value="id"                          {{-- Value key --}}
    option-label="name"                        {{-- Display key --}}
    theme="bootstrap"                          {{-- Override theme --}}
/>
```

## 5 Most Common Patterns

### 1️⃣ Simple Dropdown
```blade
<x-searchable-select :options="$countries" wire-model="country_id" :selected-value="$country_id" />
```

### 2️⃣ Multi-Select
```blade
<x-searchable-select :options="$skills" wire-model="skills" :selected-value="$skills" :multiple="true" />
```

### 3️⃣ Cascading Dropdowns
```blade
<x-searchable-select wire-model.live="country_id" :options="$countries" />
<x-searchable-select wire-model="city_id" :options="$cities" :disabled="!$country_id" />
```

### 4️⃣ API Integration
```blade
<x-searchable-select api-url="/api/users/search" :options="[]" wire-model="user_id" />
```

### 5️⃣ Grouped Options
```blade
<x-searchable-select :options="$grouped" :grouped="true" wire-model="selection" />
```

## Data Structures

### Simple Array
```php
$options = [
    ['id' => 1, 'name' => 'Option 1'],
    ['id' => 2, 'name' => 'Option 2'],
];
```

### Eloquent Collection
```php
$options = Country::orderBy('name')->get();
```

### Grouped Array
```php
$options = [
    [
        'label' => 'Group A',
        'options' => [
            ['id' => 1, 'name' => 'Item 1'],
            ['id' => 2, 'name' => 'Item 2'],
        ]
    ],
];
```

## Troubleshooting (Quick Fixes)

### Dropdown won't open
```bash
# Clear caches
php artisan view:clear
php artisan optimize:clear
```

### Selected value not showing
```blade
{{-- ✅ Always pass :selected-value --}}
<x-searchable-select :selected-value="$country_id" ... />
```

### Styling broken (Tailwind)
```js
// tailwind.config.js
export default {
  content: [
    './resources/**/*.blade.php',
    './vendor/williamug/searchable-select/resources/views/**/*.blade.php',
  ],
}
```
```bash
npm run build
```

### Styling broken (Bootstrap)
```bash
# Verify config
php artisan tinker
>>> config('searchable-select.theme');
=> "bootstrap"

# Clear caches
php artisan config:clear
php artisan view:clear
```

## Performance Guidelines

| Dataset Size | Recommendation |
|--------------|---------------|
| < 1,000      | Default (client-side) ✅ |
| 1,000+       | Use API integration ⚡ |
| 10,000+      | Must use API 🚨 |

## API Endpoint Example

```php
// routes/api.php
Route::get('/users/search', function (Request $request) {
    return [
        'data' => User::where('name', 'like', "%{$request->search}%")
            ->select('id', 'name')
            ->limit(50)
            ->get()
    ];
});
```

## Validation Example

```php
// Component
protected $rules = [
    'country_id' => 'required|exists:countries,id',
];
```

```blade
{{-- View --}}
<x-searchable-select wire-model="country_id" :options="$countries" />
@error('country_id')
    <span class="text-red-500">{{ $message }}</span>
@enderror
```

## Testing

```bash
# Run all tests
composer test

# Output: 24 passed (46 assertions)
```

## Demo App

```bash
cd demo
php artisan serve
```

Visit: `http://localhost:8000`

## Essential Commands

```bash
# Install
composer require williamug/searchable-select

# Publish config
php artisan vendor:publish --tag=searchable-select-config

# Publish views (for customization)
php artisan vendor:publish --tag=searchable-select-views

# Clear caches
php artisan config:clear && php artisan view:clear

# Run tests
composer test
```

## Documentation Files

- **README.md** - Complete documentation (1,700+ lines)
- **DOCUMENTATION.md** - Documentation overview
- **DOCS-GUIDE.md** - Visual navigation guide
- **QUICK-REFERENCE.md** - This file (you are here)
- **demo/** - Live examples

## Links

- 📦 Package: https://packagist.org/packages/williamug/searchable-select
- 🐙 GitHub: https://github.com/williamug/searchable-select
- 🐛 Issues: https://github.com/williamug/searchable-select/issues

## Pro Tips

1. ✅ Always pass `:selected-value` for reactivity
2. ✅ Use `wire:key` in loops
3. ✅ API for > 1,000 options
4. ✅ Debounce with `.live.debounce.300ms`
5. ✅ Cache static data
6. ✅ `wire-model.live` for immediate updates
7. ✅ Clear caches after config changes
8. ✅ Use Bootstrap theme per component if needed

## Support

Need help? Check:
1. README.md (comprehensive guide)
2. Troubleshooting section
3. Demo app (working examples)
4. GitHub issues

---

**Made with ❤️ for Laravel**

⭐ Star the repo if this helps you!
