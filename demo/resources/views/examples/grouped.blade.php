@extends('layout')

@section('title', 'Grouped Options - Searchable Select')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Grouped Options</h1>
            <p class="text-gray-600">Organize options into categories with group headers</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Live Demo</h2>
            @livewire('grouped-example')
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h2 class="text-lg font-semibold mb-4">Code Example</h2>

            <h3 class="text-sm font-medium text-gray-700 mb-2">Livewire Component:</h3>
            <div class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-sm overflow-x-auto mb-4">
                <pre><code><span class="text-purple-400">class</span> <span class="text-yellow-300">GroupedExample</span> <span class="text-purple-400">extends</span> <span class="text-yellow-300">Component</span>
{
    <span class="text-purple-400">public</span> $country_id;
    <span class="text-purple-400">public</span> $countries;

    <span class="text-purple-400">public function</span> <span class="text-blue-400">mount</span>()
    {
        $this->countries = [
            [<span class="text-green-400">'value'</span> => <span class="text-orange-400">1</span>, <span class="text-green-400">'label'</span> => <span class="text-green-400">'United States'</span>, <span class="text-green-400">'group'</span> => <span class="text-green-400">'North America'</span>],
            [<span class="text-green-400">'value'</span> => <span class="text-orange-400">2</span>, <span class="text-green-400">'label'</span> => <span class="text-green-400">'Canada'</span>, <span class="text-green-400">'group'</span> => <span class="text-green-400">'North America'</span>],
            [<span class="text-green-400">'value'</span> => <span class="text-orange-400">3</span>, <span class="text-green-400">'label'</span> => <span class="text-green-400">'United Kingdom'</span>, <span class="text-green-400">'group'</span> => <span class="text-green-400">'Europe'</span>],
            <span class="text-gray-500">// ...</span>
        ];
    }
}</code></pre>
            </div>

            <h3 class="text-sm font-medium text-gray-700 mb-2">Blade View:</h3>
            <div class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-sm overflow-x-auto">
                <pre><code><span class="text-purple-400">&lt;x-searchable-select</span>
    <span class="text-blue-400">:options</span>=<span class="text-green-400">"$countries"</span>
    <span class="text-blue-400">wire:model</span>=<span class="text-green-400">"country_id"</span>
    <span class="text-blue-400">placeholder</span>=<span class="text-green-400">"Select a country"</span>
<span class="text-purple-400">/&gt;</span></code></pre>
            </div>
        </div>

        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-sm font-semibold text-blue-900 mb-2">💡 Key Features</h3>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>• Automatic grouping when 'group' key is present in options</li>
                <li>• Group headers styled differently from options</li>
                <li>• Search filters across all groups</li>
                <li>• Groups collapse automatically if no matches</li>
                <li>• Works with multi-select too!</li>
            </ul>
        </div>
    </div>
@endsection
