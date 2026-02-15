# Searchable Select Component - Demo Guide

## 🚀 Quick Start

The demo app is now fully configured and ready to use!

### Start the Server

```bash
cd /home/williamdk/sites/searchable-select/demo
php artisan serve
```

Then visit: **http://127.0.0.1:8000**

---

## 📚 Available Demos

### 1. **Homepage** - `/`
Overview of all demos with navigation cards

### 2. **Basic Example** - `/basic`
- **What it shows**: Simple single-select dropdown
- **Component**: `BasicExample.php`
- **Features**:
  - Search/filter countries
  - Single selection
  - Shows selected value
  - Custom option keys (`value` and `label`)

**Code Snippet:**
```blade
<x-searchable-select
    :options="$countries"
    wire-model="country_id"
    :selected-value="$country_id"
    placeholder="Select a country..."
    option-value="value"
    option-label="label"
/>
```

---

### 3. **Multi-Select** - `/multi-select`
- **What it shows**: Multiple selection with tags
- **Component**: `MultiSelectExample.php`
- **Features**:
  - Select multiple countries at once
  - Display as tags/badges
  - Remove individual selections
  - Clear all button
  - Array binding (`$country_ids = []`)

**Code Snippet:**
```blade
<x-searchable-select
    :options="$countries"
    wire-model="country_ids"
    :selected-value="$country_ids"
    placeholder="Select countries..."
    option-value="value"
    option-label="label"
    :multiple="true"
/>
```

---

### 4. **Grouped Options** - `/grouped`
- **What it shows**: Options organized by categories
- **Component**: `GroupedExample.php`
- **Features**:
  - Countries grouped by region (North America, Europe, Asia)
  - Group headers in dropdown
  - Searchable across all groups
  - Maintains group context in display

**Data Structure:**
```php
$groupedCountries = [
    [
        'label' => 'North America',
        'options' => [
            ['value' => 1, 'label' => 'United States'],
            ['value' => 2, 'label' => 'Canada'],
            // ...
        ]
    ],
    [
        'label' => 'Europe',
        'options' => [
            ['value' => 4, 'label' => 'United Kingdom'],
            // ...
        ]
    ],
];
```

**Code Snippet:**
```blade
<x-searchable-select
    :options="$groupedCountries"
    wire-model="country_id"
    :selected-value="$country_id"
    placeholder="Select a country..."
    option-value="value"
    option-label="label"
    :grouped="true"
    group-label="label"
    group-options="options"
/>
```

---

### 5. **API Integration** - `/api`
- **What it shows**: Dynamic loading from API endpoint
- **Component**: `ApiExample.php`
- **Features**:
  - Fetches data from `/api/countries` endpoint
  - Debounced search (300ms delay)
  - Loading spinner while fetching
  - Search as you type
  - Works with empty initial options

**Code Snippet:**
```blade
<x-searchable-select
    :options="[]"
    wire-model="country_id"
    :selected-value="$country_id"
    api-url="/api/countries"
    api-search-param="search"
    placeholder="Start typing to search countries..."
    option-value="value"
    option-label="label"
/>
```

**API Endpoint (routes/web.php):**
```php
Route::get('/api/countries', function () {
    $search = request('search', '');
    $countries = [...]; // Your data source

    if ($search) {
        $countries = array_filter($countries, function ($country) use ($search) {
            return stripos($country['label'], $search) !== false;
        });
    }

    return response()->json(array_values($countries));
});
```

---

## 🎨 Component Props Reference

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `options` | Array | `[]` | Data array or collection |
| `wire-model` | String | required | Livewire property binding |
| `selected-value` | Mixed | `null` | Current selected value(s) |
| `placeholder` | String | `'Select option'` | Button placeholder text |
| `search-placeholder` | String | `'Search...'` | Search input placeholder |
| `option-value` | String | `'id'` | Key for option values |
| `option-label` | String | `'name'` | Key for option labels |
| `multiple` | Boolean | `false` | Enable multi-select |
| `clearable` | Boolean | `true` | Show clear button |
| `disabled` | Boolean | `false` | Disable component |
| `api-url` | String | `null` | API endpoint URL |
| `api-search-param` | String | `'search'` | Query param for search |
| `grouped` | Boolean | `false` | Enable grouped options |
| `group-label` | String | `'label'` | Key for group labels |
| `group-options` | String | `'options'` | Key for group options |
| `empty-message` | String | `'No options available'` | Empty state message |

---

## 🔧 How It Works

### Component Location
The component is copied to: `/demo/resources/views/components/searchable-select.blade.php`

### Usage in Livewire Components

1. **Define data in mount():**
```php
public function mount()
{
    $this->countries = [
        ['value' => 1, 'label' => 'United States'],
        // ...
    ];
}
```

2. **Use in blade view:**
```blade
<x-searchable-select
    :options="$countries"
    wire-model="country_id"
    :selected-value="$country_id"
/>
```

3. **Access selected value:**
```php
public function save()
{
    // $this->country_id contains the selected value
    dd($this->country_id);
}
```

---

## 🎯 Key Features Demonstrated

### ✅ Client-side Search
Real-time filtering as you type (Basic, Multi-Select, Grouped demos)

### ✅ Server-side Search
Dynamic API calls with debouncing (API demo)

### ✅ Multi-Selection
Select multiple items with tag display (Multi-Select demo)

### ✅ Grouped Organization
Category-based option grouping (Grouped demo)

### ✅ Dark Mode Support
Automatic dark mode styling (built into component)

### ✅ Keyboard Navigation
Arrow keys, Enter, Escape support (all demos)

### ✅ Accessibility
ARIA attributes, screen reader friendly (all demos)

### ✅ Clear Functionality
Remove all selections with one click (all demos)

### ✅ Disabled State
Conditional disabling (can be added to any demo)

---

## 🔍 Testing the Demos

### Test Basic Example
1. Go to `/basic`
2. Click the dropdown
3. Type "united" in search
4. Select "United States"
5. See the selected value displayed below

### Test Multi-Select
1. Go to `/multi-select`
2. Select multiple countries
3. Notice tags appear in the button
4. Click × on tags to remove individual selections
5. Click clear button to remove all

### Test Grouped Options
1. Go to `/grouped`
2. Notice countries organized by region
3. Search works across all groups
4. Group headers remain visible

### Test API Integration
1. Go to `/api`
2. Click the dropdown and start typing
3. Notice the loading spinner
4. See filtered results from API
5. Try: "united", "can", "jap"

---

## 📁 File Structure

```
demo/
├── app/
│   └── Livewire/
│       ├── BasicExample.php           # Basic single select
│       ├── MultiSelectExample.php     # Multiple selection
│       ├── GroupedExample.php         # Grouped options
│       └── ApiExample.php             # API integration
├── resources/
│   └── views/
│       ├── components/
│       │   ├── searchable-select.blade.php  # The component
│       │   └── layouts/
│       │       └── app.blade.php            # Layout template
│       ├── livewire/
│       │   ├── basic-example.blade.php
│       │   ├── multi-select-example.blade.php
│       │   ├── grouped-example.blade.php
│       │   └── api-example.blade.php
│       └── demo.blade.php             # Homepage
├── routes/
│   └── web.php                        # Routes + API endpoint
└── DEMO_GUIDE.md                      # This guide
```

---

## 💡 Tips for Users

### For Package Testing
- Each demo shows a different use case
- View the source code of each Livewire component to understand implementation
- Check browser console for Alpine.js/Livewire errors if something doesn't work

### For Documentation
- Screenshots can be taken from each demo page
- Code examples are production-ready
- All demos use the actual package component

### For Development
- Modify `/demo/resources/views/components/searchable-select.blade.php` to test changes
- Run `php artisan view:clear` after component modifications
- API endpoint in `routes/web.php` shows server-side filtering pattern

---

## 🐛 Troubleshooting

### Component not rendering?
```bash
php artisan view:clear
php artisan optimize:clear
```

### Dropdown not opening?
- Check browser console for JavaScript errors
- Verify Alpine.js is loaded (comes with Livewire)
- Ensure Tailwind CSS is loaded

### Selected value not showing?
- Verify `selected-value` prop matches an option value
- Check `option-value` matches your data structure
- Ensure the value exists in the options array

### API not working?
- Check `/api/countries` endpoint returns JSON
- Verify API URL is correct in component
- Check browser network tab for API calls

---

## 🎉 Success Checklist

- [x] Component installed and accessible
- [x] All demo routes working
- [x] Basic select functional
- [x] Multi-select with tags working
- [x] Grouped options displaying correctly
- [x] API integration fetching data
- [x] Search filtering works
- [x] Clear button removes selections
- [x] Responsive on mobile
- [x] Dark mode supported

---

**You're all set! The demo is fully functional and ready to showcase the package features.**
