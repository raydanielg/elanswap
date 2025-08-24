<header x-data="{ open: false }"
        class="sticky top-0 z-50 backdrop-blur supports-[backdrop-filter]:bg-primary-900/70 bg-primary-900/90 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="/" class="flex items-center group">
                <img src="{{ asset('logo.png') }}" alt="ElanSwap" class="w-8 h-8 mr-2 select-none"/>
                <span class="text-xl font-bold tracking-tight">ElanSwap</span>
            </a>

            <!-- Desktop nav (English only) -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="#home" class="text-white/80 hover:text-white transition">Home</a>
                <a href="#regions" class="text-white/80 hover:text-white transition">Regions</a>
                <a href="#about" class="text-white/80 hover:text-white transition">About</a>
                <a href="#contact" class="text-white/80 hover:text-white transition">Contact</a>
                <a href="#faq" class="text-white/80 hover:text-white transition">FAQ</a>
            </nav>

            <!-- Actions (English only, no Login) -->
            <div class="hidden md:flex items-center space-x-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-md bg-white/10 hover:bg-white/20 text-white font-semibold shadow transition">Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-md bg-white text-primary-900 font-semibold shadow hover:shadow-md transition">Get Started</a>
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

    <!-- Mobile menu (English only, no Login) -->
    <div x-show="open" class="md:hidden border-t border-primary-800 bg-primary-900 text-white">
        <div class="px-4 py-3 space-y-2">
            <a href="#home" class="block text-white/90 hover:text-white">Home</a>
            <a href="#regions" class="block text-white/90 hover:text-white">Regions</a>
            <a href="#about" class="block text-white/90 hover:text-white">About</a>
            <a href="#contact" class="block text-white/90 hover:text-white">Contact</a>
            <a href="#faq" class="block text-white/90 hover:text-white">FAQ</a>
            <div class="pt-2 border-t border-primary-800">
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-md bg-white/10 hover:bg-white/20 text-white font-semibold">Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="block mt-2 px-4 py-2 rounded-md bg-white text-primary-900 font-semibold">Get Started</a>
                @endauth
            </div>
        </div>
    </div>
</header>
