<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin | {{ config('app.name', 'ElanSwap') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/app.css') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />
    <style>[x-cloak]{ display:none !important; }</style>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-900" x-data="{ sidebar: false }">
    @include('super.partials.navbar')

    @if (session('status'))
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-4 text-green-800">
                    {{ session('status') }}
                </div>
            </div>
        </div>
    @endif

    <div class="flex min-h-screen">
        @include('super.partials.sidebar')

        <main class="flex-1 p-4 md:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

    @include('super.partials.footer')
</body>
</html>
