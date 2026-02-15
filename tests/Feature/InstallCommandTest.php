<?php

use Illuminate\Support\Facades\File;

beforeEach(function () {
  // Clean up any existing component
  cleanupComponent();
});

afterEach(function () {
  // Clean up after tests
  cleanupComponent();
});

function cleanupComponent(): void
{
  $componentPath = resource_path('views/components/searchable-select.blade.php');
  if (File::exists($componentPath)) {
    File::delete($componentPath);
  }
}

test('it can install the component', function () {
  $this->artisan('install:searchable-select')
    ->expectsOutput('🚀 Installing Livewire Searchable Select...')
    ->expectsOutput('✅ Installation complete!')
    ->assertExitCode(0);

  expect(resource_path('views/components/searchable-select.blade.php'))->toBeFile();
});

test('it creates components directory if not exists', function () {
  // Remove components directory
  $componentsDir = resource_path('views/components');
  if (File::exists($componentsDir)) {
    File::deleteDirectory($componentsDir);
  }

  $this->artisan('install:searchable-select')
    ->assertExitCode(0);

  expect($componentsDir)->toBeDirectory();
  expect(resource_path('views/components/searchable-select.blade.php'))->toBeFile();
});

test('it prompts for confirmation if component exists', function () {
  // Install once
  $this->artisan('install:searchable-select')
    ->assertExitCode(0);

  // Try to install again without force
  $this->artisan('install:searchable-select')
    ->expectsConfirmation('Component already exists. Do you want to overwrite it?', 'no')
    ->expectsOutput('Installation cancelled.')
    ->assertExitCode(1);
});

test('it can overwrite with confirmation', function () {
  // Install once
  $this->artisan('install:searchable-select')
    ->assertExitCode(0);

  // Modify the file
  File::put(
    resource_path('views/components/searchable-select.blade.php'),
    'Modified content'
  );

  // Install again with confirmation
  $this->artisan('install:searchable-select')
    ->expectsConfirmation('Component already exists. Do you want to overwrite it?', 'yes')
    ->expectsOutput('✅ Installation complete!')
    ->assertExitCode(0);

  // Verify file was overwritten
  $content = File::get(resource_path('views/components/searchable-select.blade.php'));
  expect($content)->not->toContain('Modified content');
  expect($content)->toContain('@props');
});

test('it can force overwrite without confirmation', function () {
  // Install once
  $this->artisan('install:searchable-select')
    ->assertExitCode(0);

  // Modify the file
  File::put(
    resource_path('views/components/searchable-select.blade.php'),
    'Modified content'
  );

  // Force install
  $this->artisan('install:searchable-select --force')
    ->expectsOutput('✅ Installation complete!')
    ->assertExitCode(0);

  // Verify file was overwritten
  $content = File::get(resource_path('views/components/searchable-select.blade.php'));
  expect($content)->not->toContain('Modified content');
  expect($content)->toContain('@props');
});

test('it copies correct component content', function () {
  $this->artisan('install:searchable-select')
    ->assertExitCode(0);

  $content = File::get(resource_path('views/components/searchable-select.blade.php'));

  // Check for essential component parts
  expect($content)
    ->toContain('@props')
    ->toContain('x-data')
    ->toContain('@click')
    ->toContain('placeholder')
    ->toContain('selectedValue');
});

test('it shows usage example after installation', function () {
  $this->artisan('install:searchable-select')
    ->expectsOutput('📖 Basic Usage:')
    ->expectsOutput('📚 For more examples and documentation, visit:')
    ->assertExitCode(0);
});

test('it checks livewire compatibility', function () {
  $this->artisan('install:searchable-select')
    ->expectsOutput('✓ Livewire detected')
    ->assertExitCode(0);
});
