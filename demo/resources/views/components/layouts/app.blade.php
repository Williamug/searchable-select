<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Searchable Select Demo' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-50 dark:bg-gray-900 min-h-screen">

    <header class="bg-white dark:bg-zinc-800 border-b border-gray-200 dark:border-zinc-700">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 py-5">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">williamug/searchable-select</h1>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                        Alpine.js + Tailwind · Livewire 3 &amp; 4 · No wire:ignore
                    </p>
                </div>
                <a
                    href="https://github.com/williamug/searchable-select"
                    class="text-sm text-blue-600 dark:text-blue-400 hover:underline"
                    target="_blank"
                    rel="noopener"
                >GitHub →</a>
            </div>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
        {{ $slot }}
    </main>

    <footer class="border-t border-gray-200 dark:border-zinc-700 mt-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
            Built by <a href="https://github.com/williamug" class="text-blue-600 dark:text-blue-400 hover:underline">William Asaba</a>
        </div>
    </footer>

    @livewireScripts
</body>

</html>
