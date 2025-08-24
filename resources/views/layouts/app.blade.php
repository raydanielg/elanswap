<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ElanSwap') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles & Scripts (no Vite) -->
        <link rel="stylesheet" href="{{ asset('assets/app.css') }}" />
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <style>
            :root {
                --primary-50: #f0f9ff;
                --primary-100: #e0f2fe;
                --primary-200: #bae6fd;
                --primary-300: #7dd3fc;
                --primary-400: #38bdf8;
                --primary-500: #0ea5e9;
                --primary-600: #0284c7;
                --primary-700: #0369a1;
                --primary-800: #075985;
                --primary-900: #0c4a6e;
                --primary-950: #082f49;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 h-full">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main class="flex-grow bg-gradient-to-b from-white to-blue-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    @isset($header)
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $header }}</h1>
                        </div>
                    @endisset
                    {{ $slot }}
                </div>
            </main>

            @include('layouts.partials.footer-front')
        </div>
    </body>
</html>
