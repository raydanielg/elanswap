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

            /* Fallback responsive rules in case Tailwind responsive utilities aren't present in built CSS */
            @media (min-width: 1024px) {
                .auth-left { display: flex !important; width: 50% !important; }
                .auth-right { width: 50% !important; }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 h-full">
        <div class="min-h-screen flex flex-col lg:flex-row">
            <!-- Left side - Features (hidden on small screens) -->
            <div class="hidden lg:flex lg:w-1/2 bg-primary-900 text-white p-8 lg:p-12 flex-col justify-center auth-left">
                <div class="max-w-lg mx-auto w-full">
                    <div class="flex items-center mb-8">
                        <svg class="w-10 h-10 text-white mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-2xl font-bold">ElanSwap</span>
                    </div>
                    
                    <h2 class="text-3xl font-bold mb-6">Streamline Employee Transfers with Ease</h2>
                    <p class="text-blue-100 mb-10">ElanSwap simplifies the process of employee work station transfers, making relocations faster, simpler, and more efficient for both employees and employers.</p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="flex items-center justify-center h-6 w-6 rounded-full bg-primary-700 text-primary-200">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium">Seamless Transfers</h3>
                                <p class="mt-1 text-blue-100">Effortlessly manage employee relocations between departments or locations.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="flex items-center justify-center h-6 w-6 rounded-full bg-primary-700 text-primary-200">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium">Secure Platform</h3>
                                <p class="mt-1 text-blue-100">Enterprise-grade security to protect your organization's sensitive data.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="flex items-center justify-center h-6 w-6 rounded-full bg-primary-700 text-primary-200">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium">Lightning Fast</h3>
                                <p class="mt-1 text-blue-100">Process transfers in minutes instead of days with our streamlined workflow.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-12 pt-6 border-t border-primary-800">
                    <p class="text-sm text-blue-200">Trusted by leading organizations worldwide to manage their workforce mobility needs.</p>
                </div>
            </div>

            <!-- Right side - Login Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-12 bg-white auth-right">
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
