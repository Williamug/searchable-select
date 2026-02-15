@extends('layout')

@section('title', 'API Integration - Searchable Select')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">API Integration</h1>
            <p class="text-gray-600">Load options dynamically from an API endpoint with debounced search</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Live Demo</h2>
            @livewire('api-example')
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h2 class="text-lg font-semibold mb-4">Code Example</h2>

            <h3 class="text-sm font-medium text-gray-700 mb-2">Livewire Component:</h3>
            <div class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-sm overflow-x-auto mb-4">
                <pre><code><span class="text-purple-400">class</span> <span class="text-yellow-300">ApiExample</span> <span class="text-purple-400">extends</span> <span class="text-yellow-300">Component</span>
{
    <span class="text-purple-400">public</span> $country_id;

    <span class="text-gray-500">// No need to populate options - fetched from API!</span>
}</code></pre>
            </div>

            <h3 class="text-sm font-medium text-gray-700 mb-2">Blade View:</h3>
            <div class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-sm overflow-x-auto mb-4">
                <pre><code><span class="text-purple-400">&lt;x-searchable-select</span>
    <span class="text-blue-400">wire:model</span>=<span class="text-green-400">"country_id"</span>
    <span class="text-blue-400">placeholder</span>=<span class="text-green-400">"Search countries..."</span>
    <span class="text-blue-400">apiUrl</span>=<span class="text-green-400">"/api/countries"</span>
    <span class="text-blue-400">apiSearchParam</span>=<span class="text-green-400">"search"</span>
<span class="text-purple-400">/&gt;</span></code></pre>
            </div>

            <h3 class="text-sm font-medium text-gray-700 mb-2">API Endpoint (Laravel Route):</h3>
            <div class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-sm overflow-x-auto">
                <pre><code><span class="text-purple-400">Route::</span><span class="text-blue-400">get</span>(<span class="text-green-400">'/api/countries'</span>, <span class="text-purple-400">function</span> () {
    $search = <span class="text-blue-400">request</span>(<span class="text-green-400">'search'</span>, <span class="text-green-400">''</span>);

    $countries = <span class="text-blue-400">Country</span>::<span class="text-blue-400">query</span>()
        -><span class="text-blue-400">when</span>($search, <span class="text-purple-400">fn</span>($q) => $q-><span class="text-blue-400">where</span>(<span class="text-green-400">'name'</span>, <span class="text-green-400">'like'</span>, <span class="text-green-400">"%{$search}%"</span>))
        -><span class="text-blue-400">select</span>(<span class="text-green-400">'id as value'</span>, <span class="text-green-400">'name as label'</span>)
        -><span class="text-blue-400">get</span>();

    <span class="text-purple-400">return</span> <span class="text-blue-400">response</span>()-><span class="text-blue-400">json</span>($countries);
});</code></pre>
            </div>
        </div>

        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-sm font-semibold text-blue-900 mb-2">💡 Key Features</h3>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>• Automatic debouncing (300ms) to reduce API calls</li>
                <li>• Loading indicator while fetching data</li>
                <li>• Works with large datasets without performance issues</li>
                <li>• API returns: <code class="bg-blue-100 px-1 rounded">[{value: 1, label: 'USA'}, ...]</code></li>
                <li>• Supports pagination and server-side filtering</li>
            </ul>
        </div>
    </div>
@endsection
