<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Searchable Select Demo' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Searchable Select Component</h1>
                        <p class="mt-2 text-gray-600">Beautiful, searchable dropdown for Laravel Livewire</p>
                    </div>
                    <a href="/" class="text-blue-600 hover:text-blue-800">← Back to Home</a>
                </div>
            </div>
        </header>

        <!-- Navigation -->
        <nav class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex space-x-8 py-4">
                    <a href="/basic"
                        class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-100 {{ request()->is('basic') ? 'bg-gray-100 font-semibold' : '' }}">Basic</a>
                    <a href="/multi-select"
                        class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-100 {{ request()->is('multi-select') ? 'bg-gray-100 font-semibold' : '' }}">Multi-Select</a>
                    <a href="/grouped"
                        class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-100 {{ request()->is('grouped') ? 'bg-gray-100 font-semibold' : '' }}">Grouped</a>
                    <a href="/api"
                        class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-100 {{ request()->is('api') ? 'bg-gray-100 font-semibold' : '' }}">API</a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <p class="text-center text-gray-600">
                    Built with ❤️ by <a href="https://github.com/williamug"
                        class="text-blue-600 hover:text-blue-800">William Asaba</a>
                </p>
            </div>
        </footer>
    </div>

    @livewireScripts
</body>

</html>
