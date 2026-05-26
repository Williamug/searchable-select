<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Searchable Select Demo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    @livewireStyles
</head>
<body class="bg-light">

<div class="container py-5">

    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold">
            Searchable Select Demo
        </h1>

        <p class="lead text-muted">
            Demo playground for the AJJI-Tech searchable select package.
        </p>

        <div class="alert alert-primary mt-4">
            This page is used to test:
            <ul class="mb-0 mt-2 text-start">
                <li>Array data source</li>
                <li>Eloquent model data source</li>
                <li>Bootstrap forms</li>
                <li>Bootstrap modal support</li>
                <li>Livewire integration</li>
                <li>Dark/light compatibility</li>
            </ul>
        </div>
    </div>

    @livewire('testsearchable')

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

@livewireScripts
</body>
</html>
