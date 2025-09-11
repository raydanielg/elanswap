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
            /* Global page loader styles */
            #page-loader { position: fixed; inset: 0; z-index: 1000; display: flex; align-items: center; justify-content: center; background: rgba(12,74,110,.72); backdrop-filter: blur(2px); }
            .loader { width: 48px; height: 48px; display: inline-block; position: relative; }
            .loader::after,
            .loader::before { content: ''; box-sizing: border-box; width: 48px; height: 48px; border-radius: 50%; background: #FFF; position: absolute; left: 0; top: 0; animation: animloader 2s linear infinite; }
            .loader::after { animation-delay: 1s; }
            @keyframes animloader {
              0% { transform: scale(0); opacity: 1; }
              100% { transform: scale(1); opacity: 0; }
            }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 h-full">
        <!-- Global Page Loader: visible by default, hidden after load via JS -->
        <div id="page-loader"><span class="loader" aria-label="Loading"></span></div>
        <noscript><style>#page-loader{display:none}</style></noscript>

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
                    @hasSection('content')
                        @yield('content')
                    @else
                        {{ $slot ?? '' }}
                    @endif
                </div>
            </main>

            @include('layouts.partials.footer-front')
        </div>
        <script>
            // Expose unpaid status globally for modal trigger logic
            window.UNPAID = @json((Auth::check() && method_exists(Auth::user(), 'hasPaid')) ? !Auth::user()->hasPaid() : true);
        </script>
        @include('partials.payment-required-modal')

        @stack('scripts')
        <script>
            (function(){
                const loader = document.getElementById('page-loader');
                function hideLoader(){ if (loader) loader.style.display = 'none'; }
                function showLoader(){ if (loader) loader.style.display = 'flex'; }
                // Hide on full load
                window.addEventListener('load', hideLoader);
                // Show on same-origin navigations
                document.addEventListener('click', function(e){
                    const a = e.target.closest('a');
                    if (!a) return;
                    const href = a.getAttribute('href');
                    const target = a.getAttribute('target');
                    const download = a.hasAttribute('download');
                    if (!href || href.startsWith('#') || href.startsWith('javascript:') || download || (target && target !== '_self')) return;
                    try {
                        const url = new URL(href, window.location.origin);
                        if (url.origin === window.location.origin) {
                            showLoader();
                        }
                    } catch(_) { /* ignore */ }
                });
                // Forms
                document.addEventListener('submit', function(e){ showLoader(); });
                // Page show (back cache) ensure hidden
                window.addEventListener('pageshow', function(e){ if (e.persisted) hideLoader(); });
            })();
        </script>
    </body>
</html>
