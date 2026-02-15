@extends('layout')

@section('title', 'Multi-Select Example - Searchable Select')
@section('description', 'Select multiple options with elegant UI')

@section('content')
    <div class="space-y-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Multi-Select Example</h2>
            <p class="text-gray-600 mb-8">This example shows how to select multiple options at once with a clean,
                user-friendly interface.</p>
        </div>

        <div class="bg-white rounded-lg shadow p-8 max-w-2xl">
            @livewire('multi-select-example')
        </div>

        <div class="bg-gray-100 rounded-lg p-6 max-w-2xl">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Code Example</h3>
            <pre class="bg-gray-900 text-gray-100 p-4 rounded overflow-x-auto"><code>&lt;livewire:searchable-select
    name="country_ids"
    :options="$countries"
    placeholder="Select countries"
    :multiple="true"
    wire:model="country_ids"
/&gt;</code></pre>
        </div>

        <div class="bg-green-50 border-l-4 border-green-400 p-4 max-w-2xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        <strong>Feature:</strong> Selected items are displayed as badges that can be removed individually.
                        Click on any badge to remove that selection.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
