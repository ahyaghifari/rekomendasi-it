<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Surat Rekomendasi IT</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-5xl items-center gap-3 px-4 py-3 sm:px-6 lg:px-8">
            <img src="{{ asset('images/logo-syifa.jpeg') }}" alt="Logo Syifa Global Group" class="h-14 w-auto sm:h-16">
            <div class="leading-tight">
                <div class="text-sm font-bold text-slate-900">Surat Rekomendasi IT</div>
                <div class="text-xs text-slate-500">Syifa Global Group</div>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
