<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PyLearn') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600;inter:400,500,600,700&display=swap" rel="stylesheet"/>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-b from-purple-50 via-white to-white text-gray-900">
    <div class="relative min-h-screen">

        <!-- soft ambient blobs -->
        <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 -top-24 h-72 bg-hero"></div>

        <!-- Top navigation -->
        @include('layouts.navigation')

        @isset($header)
            <header class="sticky top-0 z-30 backdrop-blur bg-white/70 border-b border-purple-100">
                <div class="container-app py-5">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="container-app py-8">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="mt-12">
            <div class="container-app py-8 text-sm text-gray-500 flex items-center justify-between border-t border-purple-100">
                <span>© {{ date('Y') }} PyLearn — Python for Non-CS Students</span>
                <span>Made with 💜</span>
            </div>
        </footer>
    </div>
</body>
</html>
