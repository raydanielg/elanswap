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

            <!-- Footer -->
            <footer class="bg-primary-900 text-white py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="mb-4 md:mb-0">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 mr-2 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-xl font-bold">ElanSwap</span>
                            </div>
                            <p class="mt-2 text-sm text-blue-200">Connecting students and tutors for better learning experiences</p>
                        </div>
                        <div class="flex space-x-6">
                            <a href="#" class="text-blue-200 hover:text-white transition">About</a>
                            <a href="#" class="text-blue-200 hover:text-white transition">Contact</a>
                            <a href="#" class="text-blue-200 hover:text-white transition">Privacy</a>
                            <a href="#" class="text-blue-200 hover:text-white transition">Terms</a>
                        </div>
                    </div>
                    <div class="mt-6 pt-6 border-t border-blue-800 text-center text-sm text-blue-300">
                        &copy; {{ date('Y') }} ElanSwap. All rights reserved.
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
