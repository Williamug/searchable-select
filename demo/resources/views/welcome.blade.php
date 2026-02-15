@extends('layout')

@section('title', 'Searchable Select - Interactive Demo')

@section('content')
    <div class="text-center py-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Searchable Select Component</h1>
        <p class="text-xl text-gray-600 mb-8">A powerful, feature-rich searchable select component for Laravel Livewire</p>

        <div class="flex justify-center gap-4 mb-12">
            <a href="/basic" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 font-medium">
                View Examples
            </a>
            <a href="https://github.com/Williamug/searchable-select" target="_blank"
                class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 font-medium">
                GitHub
            </a>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto text-left">
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="text-indigo-600 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-2">Fast & Optimized</h3>
                <p class="text-gray-600">Built with performance in mind. Alpine.js-powered filtering and pre-cached data for
                    instant responses.</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="text-indigo-600 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-2">Feature Rich</h3>
                <p class="text-gray-600">Multi-select, grouped options, API integration, clear button, and more out of the
                    box.</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="text-indigo-600 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-2">Easy to Use</h3>
                <p class="text-gray-600">Simple API. Just pass your data and go. No complex configuration required.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border p-8 max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold mb-4">Quick Start</h2>
        <div class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-sm overflow-x-auto">
            <div class="mb-4">
                <span class="text-gray-500"># Install via Composer</span><br>
                <span class="text-green-400">composer require</span> williamug/searchable-select
            </div>
            <div class="mb-4">
                <span class="text-gray-500"># Install the component</span><br>
                <span class="text-green-400">php artisan</span> searchable-select:install
            </div>
            <div>
                <span class="text-gray-500"># Use in your Livewire component</span><br>
                <span class="text-purple-400">&lt;x-searchable-select</span><br>
                <span class="ml-4">:options=<span class="text-yellow-300">"$countries"</span></span><br>
                <span class="ml-4">wire:model=<span class="text-yellow-300">"country_id"</span></span><br>
                <span class="ml-4">placeholder=<span class="text-yellow-300">"Select a country"</span></span><br>
                <span class="text-purple-400">/&gt;</span>
            </div>
        </div>
    </div>
@endsection
