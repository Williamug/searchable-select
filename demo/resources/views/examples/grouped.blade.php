@extends('layout')

@section('title', 'Grouped Options - Searchable Select')
@section('description', 'Organize options into logical groups')

@section('content')
    <div class="space-y-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Grouped Options Example</h2>
            <p class="text-gray-600 mb-8">This example demonstrates how to organize options into groups for better
                organization and user experience.</p>
        </div>

        <div class="bg-white rounded-lg shadow p-8 max-w-2xl">
            @livewire('grouped-example')
        </div>

        <div class="bg-gray-100 rounded-lg p-6 max-w-2xl">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Code Example</h3>
            <pre class="bg-gray-900 text-gray-100 p-4 rounded overflow-x-auto"><code>&lt;livewire:searchable-select
    name="country_id"
    :options="$groupedCountries"
    placeholder="Select a country"
    :grouped="true"
    wire:model="country_id"
/&gt;</code></pre>
        </div>

        <div class="bg-purple-50 border-l-4 border-purple-400 p-4 max-w-2xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-purple-700">
                        <strong>Organization:</strong> Group headers help users quickly navigate to the category they need,
                        improving the overall user experience.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
