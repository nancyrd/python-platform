<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PyLearn') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600;inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gradient-to-b from-purple-50 via-white to-white">
    <div class="relative min-h-screen flex items-center justify-center py-12">
        <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 -top-24 h-72 bg-hero"></div>

        <div class="w-full max-w-md">
            <a href="/" class="mx-auto flex items-center justify-center gap-3 mb-6">
                <span class="text-3xl">📚</span>
                <span class="font-bold text-xl tracking-tight text-purple-700">PyLearn</span>
            </a>

            <div class="card p-6 sm:p-8">
                {{ $slot }}
            </div>

            <p class="mt-6 text-center text-xs text-gray-500">© {{ date('Y') }} PyLearn</p>
        </div>
    </div>
</body>
</html>
