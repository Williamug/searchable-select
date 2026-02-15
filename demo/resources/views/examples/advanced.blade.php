@extends('layout')

@section('title', 'Advanced Features - Searchable Select')
@section('description', 'Advanced features and customization options')

@section('content')
    <div class="space-y-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Advanced Features</h2>
            <p class="text-gray-600 mb-8">Explore advanced customization options and features available in the Searchable
                Select component.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Custom Styling -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Custom Styling</h3>
                <p class="text-gray-600 mb-4">Fully customizable with Tailwind CSS classes.</p>
                <div class="bg-gray-100 p-4 rounded">
                    <code class="text-sm">class="custom-select"</code>
                </div>
            </div>

            <!-- Validation -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Validation</h3>
                <p class="text-gray-600 mb-4">Works seamlessly with Laravel validation rules.</p>
                <div class="bg-gray-100 p-4 rounded">
                    <code class="text-sm">required|exists:countries,id</code>
                </div>
            </div>

            <!-- Accessibility -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Accessibility</h3>
                <p class="text-gray-600 mb-4">Built with accessibility in mind, keyboard navigation supported.</p>
                <div class="bg-gray-100 p-4 rounded">
                    <code class="text-sm">aria-label, role, tabindex</code>
                </div>
            </div>

            <!-- Performance -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Performance</h3>
                <p class="text-gray-600 mb-4">Optimized for handling large datasets efficiently.</p>
                <div class="bg-gray-100 p-4 rounded">
                    <code class="text-sm">Virtual scrolling, debouncing</code>
                </div>
            </div>
        </div>

        <div class="bg-indigo-50 border-l-4 border-indigo-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-indigo-700">
                        <strong>Documentation:</strong> For detailed documentation and more examples, visit the
                        <a href="https://github.com/williamug/searchable-select" class="underline font-semibold"
                            target="_blank">GitHub repository</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
