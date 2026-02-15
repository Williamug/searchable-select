<?php

return [
  'name' => env('APP_NAME', 'Searchable Select Demo'),
  'env' => env('APP_ENV', 'production'),
  'debug' => (bool) env('APP_DEBUG', false),
  'url' => env('APP_URL', 'http://localhost'),
  'timezone' => 'UTC',
  'locale' => 'en',
  'fallback_locale' => 'en',
  'key' => env('APP_KEY'),
  'cipher' => 'AES-256-CBC',
  'providers' => [
    // Laravel Framework Service Providers...
    Illuminate\Foundation\Providers\FoundationServiceProvider::class,
    Illuminate\Routing\RoutingServiceProvider::class,
    Illuminate\View\ViewServiceProvider::class,

    // Package Service Providers...
    Livewire\LivewireServiceProvider::class,
    Williamug\SearchableSelect\SearchableSelectServiceProvider::class,
  ],
];
