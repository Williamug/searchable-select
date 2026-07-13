# Changelog

All notable changes to `livewire-searchable-select` will be documented in this file.

## 1.1.0 - 2026-05-19

### Added

- **`option-subtitle`** prop — display a second descriptive line inside each option row
- **`option-icon`** prop — display an image thumbnail (URL) alongside each option label
- **`searchable`** prop — hide the search input for short lists where search adds no value
- **`max-height`** prop — configure the options list height with any valid CSS value (`px`, `rem`, `vh`)
- **`min-length`** prop — suppress client-side filtering until a minimum number of characters are typed; shows a contextual hint below the threshold
- **`placement`** prop — force the dropdown to open `'top'`, `'bottom'`, or `'auto'` (space-aware)
- **`max-tags`** prop — cap the number of visible tags in multi-select; excess items collapse to a "+N more" badge
- **`async`** prop — server-side search mode; dispatches `searchable-select:search` Livewire event on each keystroke with `{ query, key }` payload; shows a loading spinner while waiting for results
- **Select all / Clear all** buttons in the multi-select dropdown header
- **`x-model` support** — use the component in any Alpine.js context without Livewire via `x-modelable`

### Fixed

- Keyboard navigation (ArrowUp / ArrowDown / Enter / Escape) now works correctly when focus is inside the teleported dropdown (search input or options list), not just on the trigger element

## 1.0.0 - 2026-02-15

- Initial release
- Searchable dropdown component for Livewire 3 & 4
- Support for dependent/cascading dropdowns
- Dark mode support
- Custom styling support
- Installation command: `php artisan install:searchable-select`
- Comprehensive test suite with PHPUnit
- GitHub Actions CI/CD workflow
- Full documentation and examples
