<?php

namespace Williamug\SearchableSelect\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallSearchableSelectCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'install:searchable-select {--force : Overwrite existing component}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Install the Searchable Select component for Livewire';

  /**
   * Execute the console command.
   */
  public function handle(): int
  {
    $this->info('🚀 Installing Livewire Searchable Select...');
    $this->newLine();

    // Check Livewire version
    $this->checkLivewireVersion();

    // Create components directory if it doesn't exist
    $componentsPath = resource_path('views/components');
    if (! File::exists($componentsPath)) {
      File::makeDirectory($componentsPath, 0755, true);
      $this->info('✓ Created components directory');
    }

    // Copy the component file
    $sourcePath = __DIR__ . '/../../resources/views/searchable-select.blade.php';
    $destinationPath = resource_path('views/components/searchable-select.blade.php');

    if (File::exists($destinationPath) && ! $this->option('force')) {
      if (! $this->confirm('Component already exists. Do you want to overwrite it?', false)) {
        $this->warn('Installation cancelled.');

        return self::FAILURE;
      }
    }

    File::copy($sourcePath, $destinationPath);
    $this->info('✓ Copied searchable-select.blade.php to resources/views/components/');

    $this->newLine();
    $this->info('✅ Installation complete!');
    $this->newLine();

    // Show usage example
    $this->showUsageExample();

    return self::SUCCESS;
  }

  /**
   * Check Livewire version compatibility.
   */
  protected function checkLivewireVersion(): void
  {
    if (! class_exists(\Livewire\Component::class)) {
      $this->warn('⚠️  Livewire not detected. Please install Livewire 3 or 4:');
      $this->line('   composer require livewire/livewire');
      $this->newLine();

      return;
    }

    $livewireVersion = $this->getLivewireVersion();

    if ($livewireVersion) {
      if (version_compare($livewireVersion, '3.0.0', '>=')) {
        $this->info("✓ Livewire {$livewireVersion} detected (compatible)");
      } else {
        $this->warn("⚠️  Livewire {$livewireVersion} detected. This package requires Livewire 3 or 4.");
      }
    } else {
      $this->info('✓ Livewire detected');
    }
  }

  /**
   * Get installed Livewire version.
   */
  protected function getLivewireVersion(): ?string
  {
    $composerLockPath = base_path('composer.lock');

    if (!file_exists($composerLockPath)) {
      return null;
    }

    $composerLock = json_decode(file_get_contents($composerLockPath), true);

    foreach ($composerLock['packages'] ?? [] as $package) {
      if ($package['name'] === 'livewire/livewire') {
        return ltrim($package['version'], 'v');
      }
    }

    return null;
  }

  /**
   * Show usage example.
   */
  protected function showUsageExample(): void
  {
    $this->line('📖 <fg=cyan>Basic Usage:</fg=cyan>');
    $this->newLine();

    $this->line('<fg=gray>In your Livewire component view:</fg=gray>');
    $this->line('<fg=green><x-searchable-select</fg=green>');
    $this->line('<fg=green>    :options="$countries"</fg=green>');
    $this->line('<fg=green>    wire-model="country_id"</fg=green>');
    $this->line('<fg=green>    :selected-value="$country_id"</fg=green>');
    $this->line('<fg=green>    placeholder="Select Country"</fg=green>');
    $this->line('<fg=green>    search-placeholder="Search countries..."</fg=green>');
    $this->line('<fg=green>/></fg=green>');

    $this->newLine();
    $this->line('📚 For more examples and documentation, visit:');
    $this->line('   <fg=blue>https://github.com/williamug/livewire-searchable-select</fg=blue>');
    $this->newLine();
  }
}
