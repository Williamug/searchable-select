@extends('layout')

@section('title', 'Advanced Example - Searchable Select')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Advanced: All Features Combined</h1>
            <p class="text-gray-600">Multi-select + Grouped options + Clear button</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Live Demo</h2>
            @livewire('grouped-example')
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h2 class="text-lg font-semibold mb-4">Code Example</h2>

            <div class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-sm overflow-x-auto">
                <pre><code><span class="text-purple-400">&lt;x-searchable-select</span>
    <span class="text-blue-400">:options</span>=<span class="text-green-400">"$countries"</span>
    <span class="text-blue-400">wire:model</span>=<span class="text-green-400">"country_ids"</span>
    <span class="text-blue-400">placeholder</span>=<span class="text-green-400">"Select countries"</span>
    <span class="text-blue-400">multiSelect</span>=<span class="text-green-400">"true"</span>
    <span class="text-blue-400">clearButton</span>=<span class="text-green-400">"true"</span>
<span class="text-purple-400">/&gt;</span></code></pre>
            </div>
        </div>

        <div class="mt-6 bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-indigo-900 mb-4">🚀 Performance Optimizations</h3>
            <div class="space-y-3 text-sm text-indigo-800">
                <div>
                    <strong>Alpine.js-powered filtering:</strong>
                    <p class="text-indigo-700">All filtering happens client-side using Alpine's computed properties for
                        instant results.</p>
                </div>
                <div>
                    <strong>Pre-cached labels:</strong>
                    <p class="text-indigo-700">Selected option labels are cached in PHP to avoid repeated lookups.</p>
                </div>
                <div>
                    <strong>Dynamic rendering:</strong>
                    <p class="text-indigo-700">Only visible options are rendered in the DOM using Alpine templates.</p>
                </div>
                <div>
                    <strong>No hardcoded values:</strong>
                    <p class="text-indigo-700">Options stored in JavaScript array, not duplicated in HTML.</p>
                </div>
            </div>
        </div>

        <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-green-900 mb-4">✨ All Available Options</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-green-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-green-900">Property</th>
                            <th class="px-4 py-2 text-left font-semibold text-green-900">Type</th>
                            <th class="px-4 py-2 text-left font-semibold text-green-900">Description</th>
                        </tr>
                    </thead>
                    <tbody class="text-green-800">
                        <tr class="border-t border-green-200">
                            <td class="px-4 py-2 font-mono">options</td>
                            <td class="px-4 py-2">array</td>
                            <td class="px-4 py-2">Array of options [{value, label, group?}]</td>
                        </tr>
                        <tr class="border-t border-green-200">
                            <td class="px-4 py-2 font-mono">wire:model</td>
                            <td class="px-4 py-2">string</td>
                            <td class="px-4 py-2">Livewire property to bind value</td>
                        </tr>
                        <tr class="border-t border-green-200">
                            <td class="px-4 py-2 font-mono">placeholder</td>
                            <td class="px-4 py-2">string</td>
                            <td class="px-4 py-2">Placeholder text when nothing selected</td>
                        </tr>
                        <tr class="border-t border-green-200">
                            <td class="px-4 py-2 font-mono">multiSelect</td>
                            <td class="px-4 py-2">boolean</td>
                            <td class="px-4 py-2">Enable multi-select mode</td>
                        </tr>
                        <tr class="border-t border-green-200">
                            <td class="px-4 py-2 font-mono">clearButton</td>
                            <td class="px-4 py-2">boolean</td>
                            <td class="px-4 py-2">Show clear all button</td>
                        </tr>
                        <tr class="border-t border-green-200">
                            <td class="px-4 py-2 font-mono">apiUrl</td>
                            <td class="px-4 py-2">string</td>
                            <td class="px-4 py-2">API endpoint for dynamic loading</td>
                        </tr>
                        <tr class="border-t border-green-200">
                            <td class="px-4 py-2 font-mono">apiSearchParam</td>
                            <td class="px-4 py-2">string</td>
                            <td class="px-4 py-2">Query parameter name for search</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
