# Searchable Select - Complete Documentation

This document provides a comprehensive overview of the Laravel Livewire Searchable Select package.

## Quick Navigation

- **[README.md](README.md)** - Full documentation (1,700+ lines)
- **[CHANGELOG.md](CHANGELOG.md)** - Version history
- **[demo/](demo/)** - Live demo application

## What's Included in the Documentation

### 1. Getting Started (Lines 1-250)
- Package overview with badges
- Feature list (16 major features)
- Requirements and compatibility
- Installation instructions
- Complete table of contents

### 2. CSS Framework Setup (Lines 251-370)
- **Tailwind CSS** - Default setup with dark mode
- **Bootstrap 5** - Full configuration guide
- **Theme Switching** - Global and per-component
- Environment variable configuration
- Asset loading instructions

### 3. Quick Start (Lines 371-470)
- Step-by-step tutorial
- Basic Livewire component creation
- Simple usage example
- First working implementation

### 4. Component Props Reference (Lines 471-620)
- Complete prop table (17 props)
- Detailed explanations for each prop
- Type information and defaults
- Usage examples for each setting
- Data mapping examples

### 5. Usage Examples (Lines 621-1100)
Each with complete code examples:
- **Basic Single Select** - Simple dropdown
- **Multi-Select** - With badges/tags
- **Dependent/Cascading Dropdowns** - Country → Region → City
- **Grouped Options** - Categorized lists
- **API/Ajax Integration** - Dynamic loading
- **Custom Keys** - Non-standard property names
- **With Validation** - Laravel validation
- **Disabled State** - Conditional disabling
- **Without Clear Button** - Hiding clear functionality
- **Using Arrays** - Plain PHP arrays

### 6. Advanced Features (Lines 1101-1250)
- Custom CSS styling (Tailwind & Bootstrap)
- Creating specialized wrapper components
- Server-side search for large datasets
- Mixing themes in one application
- Real-world implementation patterns

### 7. Customization Guide (Lines 1251-1330)
- Publishing views for HTML customization
- Dark mode support (Tailwind & Bootstrap)
- Customizing search behavior
- Alpine.js data manipulation
- Component lifecycle hooks

### 8. Troubleshooting (Lines 1331-1560)
Detailed solutions for:
- Dropdown doesn't open
- Selected value not displaying
- Styling issues (Tailwind)
- Styling issues (Bootstrap)
- Options not updating
- API integration problems
- Multi-select issues
- Validation errors
- Performance problems

Each issue includes:
- Causes
- Solutions with code examples
- Debugging steps

### 9. Performance Optimization (Lines 1561-1640)
- Dataset size guidelines table
- Optimization techniques:
  - Lazy loading with API
  - Server-side search
  - Caching strategies
  - Column selection optimization
  - Debouncing techniques

### 10. Testing (Lines 1641-1670)
- Running the test suite
- Test coverage details
- 24 tests, 46 assertions
- Parallel testing support

### 11. Demo Application (Lines 1671-1710)
- Docker and native PHP setup
- 7 demo pages with features
- Source code references
- Live examples of all features

### 12. FAQ (Lines 1711-1830)
10 frequently asked questions:
- Can I mix frameworks?
- How to implement cascading dropdowns?
- Can I customize HTML?
- Livewire compatibility?
- Multi-field search?
- Pre-selecting values?
- Dark mode support?
- Disabling specific options?
- Inertia.js compatibility?
- Adding icons to options?

### 13. Contributing (Lines 1831-1920)
- Development setup guide
- Contribution workflow (6 steps)
- Code style requirements
- Bug reporting template
- Feature request guidelines
- PSR-12 and Pest PHP info

### 14. Credits & License (Lines 1921-End)
- Author information
- Technology stack credits
- MIT License
- Support the project section
- All relevant links

## Documentation Statistics

- Total Lines: 1,723
- Code Examples: 50+
- Features Documented: 16
- Props Documented: 17
- Troubleshooting Solutions: 10+
- Usage Examples: 10+
- Optimization Techniques: 5+

## Themes Covered

### Tailwind CSS
- Complete setup guide
- Dark mode automatic support
- Purge configuration
- Build commands
- Custom class overrides

### Bootstrap 5
- CDN and npm installation
- Configuration file setup
- Environment variables
- Theme switching
- Bootstrap-specific classes

### Both Frameworks
- Per-component theme override
- Mixing in same application
- Global theme configuration
- Migration strategies

## Key Features Highlighted

1. **Zero Configuration** - Works immediately after install
2. **Framework Flexibility** - Tailwind or Bootstrap
3. **Full Livewire Integration** - 3.x and 4.x support
4. **Comprehensive Examples** - Every feature demonstrated
5. **Production Ready** - 24 passing tests
6. **Performance Optimized** - Guidelines for any dataset size
7. **Accessible** - ARIA and keyboard navigation
8. **Well Tested** - 46 assertions covering all scenarios

## Getting Started Checklist

- [ ] Read [Installation](#installation) section
- [ ] Choose your CSS framework (Tailwind/Bootstrap)
- [ ] Follow [Quick Start](#quick-start) tutorial
- [ ] Review [Component Props Reference](#component-props-reference)
- [ ] Try [Basic Single Select](#basic-single-select) example
- [ ] Explore [Usage Examples](#usage-examples) for your use case
- [ ] Check [Troubleshooting](#troubleshooting) if issues arise
- [ ] Run the [Demo Application](#demo-application) locally
- [ ] Read [Performance Optimization](#performance-optimization) for large datasets

## Best Practices

Based on the documentation:

1. **Always pass `:selected-value`** for reactivity
2. **Use `wire:key`** in loops
3. **Use API integration** for > 1,000 options
4. **Debounce** dependent dropdowns (300-500ms)
5. **Cache** static options (countries, etc.)
6. **Select only needed columns** from database
7. **Add validation** with `@error` directives
8. **Clear caches** after config changes
9. **Use `wire-model.live`** for immediate updates
10. **Test both themes** if using per-component override

## Common Patterns

### Pattern 1: Country Selector
```blade
<x-searchable-select
    :options="Country::orderBy('name')->get()"
    wire-model="country_id"
    :selected-value="$country_id"
    placeholder="Select your country"
/>
```

### Pattern 2: Multi-Select Tags
```blade
<x-searchable-select
    :options="$skills"
    wire-model="selected_skills"
    :selected-value="$selected_skills"
    :multiple="true"
/>
```

### Pattern 3: API Search
```blade
<x-searchable-select
    api-url="/api/users/search"
    :options="[]"
    wire-model="user_id"
/>
```

### Pattern 4: Cascading Dropdowns
```blade
<x-searchable-select wire-model.live="country_id" />
<x-searchable-select :disabled="!$country_id" wire-model="city_id" />
```

## Pro Tips

1. **Theme Switching**: Use `.env` for environment-specific themes
2. **Performance**: API integration is faster than loading 10k+ records
3. **Validation**: Use `validateOnly()` for real-time feedback
4. **Caching**: Cache country/language lists (they rarely change)
5. **Debugging**: Use browser DevTools → Alpine.js extension
6. **Testing**: Run `composer test` before committing changes
7. **Customization**: Publish views only if you need HTML changes
8. **Updates**: Use `composer update williamug/searchable-select`

## Support Resources

- GitHub Issues: https://github.com/williamug/searchable-select/issues
- Documentation: [README.md](README.md)
- Demo App: [demo/README.md](demo/README.md)
- Tests: Run `composer test` for examples
- Package: https://packagist.org/packages/williamug/searchable-select

## Next Steps

After reading the documentation:

1. Install the package
2. Choose your theme (Tailwind/Bootstrap)
3. **Try** the Quick Start example
4. **Run** the demo application
5. **Implement** your first searchable select
6. **Explore** advanced features (multi-select, API, etc.)
7. **Optimize** for your dataset size
8. **Contribute** improvements back!

---

**Made with ❤️ for the Laravel community**

For the complete documentation, see [README.md](README.md)
