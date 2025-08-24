<header x-data="{ open: false }" class="sticky top-0 z-50 backdrop-blur supports-[backdrop-filter]:bg-primary-900/70 bg-primary-900/90 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="/" class="flex items-center group">
                <img src="{{ asset('logo.png') }}" alt="ElanSwap" class="w-8 h-8 rounded mr-2 ring-1 ring-white/20 group-hover:scale-105 transition"/>
                <span class="text-xl font-bold tracking-tight">ElanSwap</span>
            </a>

            <!-- Desktop nav -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="#home" class="text-blue-200 hover:text-white transition">Home</a>
                <a href="#regions" class="text-blue-200 hover:text-white transition">Regions</a>
                <a href="#about" class="text-blue-200 hover:text-white transition">About</a>
                <a href="#contact" class="text-blue-200 hover:text-white transition">Contact</a>
                <a href="#faq" class="text-blue-200 hover:text-white transition">FAQ</a>
            </nav>

            <!-- Actions -->
            <div class="hidden md:flex items-center space-x-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-md bg-white text-primary-900 font-semibold shadow hover:shadow-md transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-md border border-blue-300 text-blue-100 hover:bg-primary-800 transition">Login</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-md bg-gradient-to-r from-emerald-400 to-emerald-500 text-primary-900 font-semibold shadow hover:shadow-md transition">Get Started</a>
                @endauth
            </div>

            <!-- Mobile toggler -->
            <button @click="open = !open" class="md:hidden p-2 rounded hover:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-white">
                <svg x-show="!open" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="open" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open" class="md:hidden border-t border-primary-800">
        <div class="px-4 py-3 space-y-2">
            <a href="#home" class="block text-blue-200 hover:text-white">Home</a>
            <a href="#regions" class="block text-blue-200 hover:text-white">Regions</a>
            <a href="#about" class="block text-blue-200 hover:text-white">About</a>
            <a href="#contact" class="block text-blue-200 hover:text-white">Contact</a>
            <a href="#faq" class="block text-blue-200 hover:text-white">FAQ</a>
            <div class="pt-2 border-t border-primary-800">
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-md bg-white text-primary-900 font-semibold">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2 rounded-md border border-blue-300 text-blue-100 hover:bg-primary-800">Login</a>
                    <a href="{{ route('register') }}" class="block mt-2 px-4 py-2 rounded-md bg-gradient-to-r from-emerald-400 to-emerald-500 text-primary-900 font-semibold">Get Started</a>
                @endauth
            </div>
        </div>
    </div>
</header>
