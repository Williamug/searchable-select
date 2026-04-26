<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Theme — Searchable Select Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @livewireStyles
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="/">Searchable Select</a>
            <div class="d-flex gap-3">
                <a href="/" class="nav-link text-white-50">← Tailwind demos</a>
                <span class="nav-link text-white fw-semibold">Bootstrap demo</span>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">

                <div class="mb-4">
                    <h1 class="h3 fw-bold mb-1">Bootstrap Theme</h1>
                    <p class="text-muted">
                        All examples below use <code>theme="bootstrap"</code>. No Tailwind CSS on this page —
                        only Bootstrap 5 and Alpine.js (bundled with Livewire).
                    </p>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <livewire:bootstrap-demo />
                    </div>
                </div>

            </div>
        </div>
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
