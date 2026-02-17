# Bootstrap Support Implementation

This document outlines the Bootstrap 5 support added to the searchable-select package.

## Architecture

The package now supports both **Tailwind CSS** and **Bootstrap 5** using a config-based theme system with separate view files.

### Files Created/Modified

#### New Files
1. **`config/searchable-select.php`** - Configuration file for theme selection
2. **`resources/views/searchable-select-bootstrap.blade.php`** - Bootstrap 5 variant view
3. **`tests/Feature/BootstrapThemeTest.php`** - Tests for Bootstrap theme functionality
4. **`demo/resources/views/bootstrap-demo.blade.php`** - Bootstrap demo page
5. **`demo/resources/views/livewire/bootstrap-demo.blade.php`** - Bootstrap demo component view
6. **`demo/app/Livewire/BootstrapDemo.php`** - Bootstrap demo Livewire component

#### Modified Files
1. **`src/SearchableSelectServiceProvider.php`** - Added config merging and publishing
2. **`resources/views/searchable-select.blade.php`** - Added theme detection and routing logic
3. **`README.md`** - Updated documentation with Bootstrap support
4. **`demo/routes/web.php`** - Added Bootstrap demo route

## How It Works

### 1. Theme Selection Hierarchy
1. **Component prop** (highest priority): `<x-searchable-select theme="bootstrap" />`
2. **Config file**: `config/searchable-select.php` → `'theme' => 'bootstrap'`
3. **Environment variable**: `.env` → `SEARCHABLE_SELECT_THEME=bootstrap`
4. **Default**: `'tailwind'`

### 2. View Routing
The main `searchable-select.blade.php` view checks the theme and delegates to the appropriate view:

```php
@php
    $currentTheme = $theme ?? config('searchable-select.theme', 'tailwind');

    if ($currentTheme === 'bootstrap') {
        echo view('searchable-select::searchable-select-bootstrap', get_defined_vars())->render();
        return;
    }
@endphp
```

### 3. Class Mapping

| Element | Tailwind CSS | Bootstrap 5 |
|---------|--------------|-------------|
| Container | `border border-gray-300 rounded-lg` | `form-select border rounded` |
| Tags (multi-select) | `bg-blue-100 text-blue-800 rounded` | `badge bg-primary rounded-pill` |
| Dropdown panel | `absolute z-50 shadow-lg rounded-lg` | `dropdown-menu show position-absolute shadow-lg` |
| Options | `hover:bg-gray-100` | `dropdown-item` |
| Selected option | `bg-blue-50 text-blue-700` | `active text-white` |
| Group headers | `bg-gray-50 text-gray-500` | `dropdown-header bg-light text-muted` |
| Loading spinner | Tailwind SVG animation | Bootstrap `spinner-border` |

## Testing

All tests pass (22 tests, 44 assertions):
- ✅ Tailwind theme default behavior
- ✅ Bootstrap theme via config
- ✅ Per-component theme override
- ✅ Bootstrap single select rendering
- ✅ Bootstrap multi-select with badges
- ✅ Bootstrap grouped options

## Usage Examples

### Global Configuration

```bash
php artisan vendor:publish --tag=searchable-select-config
```

Edit `config/searchable-select.php`:
```php
return [
    'theme' => 'bootstrap',
];
```

Or use `.env`:
```env
SEARCHABLE_SELECT_THEME=bootstrap
```

### Per-Component Override

```blade
{{-- Use Bootstrap for this component --}}
<x-searchable-select theme="bootstrap" :options="$countries" wire-model="country_id" />

{{-- Use Tailwind for this component --}}
<x-searchable-select theme="tailwind" :options="$cities" wire-model="city_id" />
```

### Demo

Visit `/bootstrap` route in the demo app to see Bootstrap theme in action.

## Benefits

1. **Framework Flexibility** - Users can choose their preferred CSS framework
2. **Clean Separation** - No CSS pollution between frameworks
3. **Easy Maintenance** - Update each framework independently
4. **Performance** - No runtime class mapping overhead
5. **Extensibility** - Easy to add more frameworks in the future
6. **Backward Compatible** - Existing Tailwind users unaffected (default behavior)

## Future Enhancements

Potential additions:
- Bulma support
- Foundation support
- UIKit support
- Dark mode variants for Bootstrap
- Custom theme builder
