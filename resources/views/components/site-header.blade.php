<header x-data="{ open: false }"
        class="sticky top-0 z-50 backdrop-blur supports-[backdrop-filter]:bg-primary-900/70 bg-primary-900/90 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="/" class="flex items-center group">
                <img src="{{ asset('logo.png') }}" alt="ElanSwap" class="w-8 h-8 mr-2 select-none"/>
                <span class="text-xl font-bold tracking-tight">ElanSwap</span>
            </a>

            <!-- Desktop nav -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="#home" class="text-white/80 hover:text-white transition"><span x-text="$store.ui.t('home')">Home</span></a>
                <a href="#regions" class="text-white/80 hover:text-white transition"><span x-text="$store.ui.t('regions')">Regions</span></a>
                <a href="#about" class="text-white/80 hover:text-white transition"><span x-text="$store.ui.t('about')">About</span></a>
                <a href="#contact" class="text-white/80 hover:text-white transition"><span x-text="$store.ui.t('contact')">Contact</span></a>
                <a href="#faq" class="text-white/80 hover:text-white transition"><span x-text="$store.ui.t('faq')">FAQ</span></a>
            </nav>

            <!-- Actions -->
            <div class="hidden md:flex items-center space-x-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-md bg-white/10 hover:bg-white/20 text-white font-semibold shadow transition"><span x-text="$store.ui.t('dashboard')">Dashboard</span></a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-md border border-white/30 text-white/90 hover:bg-white/10 transition"><span x-text="$store.ui.t('login')">Login</span></a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-md bg-white text-primary-900 font-semibold shadow hover:shadow-md transition"><span x-text="$store.ui.t('get_started')">Get Started</span></a>
                @endauth

                <!-- Language -->
                <div class="relative" x-data="{openLang:false}">
                    <button @click="openLang=!openLang" class="px-3 py-2 rounded-md border border-white/30 text-sm flex items-center gap-2 text-white/90 hover:bg-white/10">
                        <span x-text="$store.ui.lang.toUpperCase()">EN</span>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
                    </button>
                    <div x-show="openLang" @click.away="openLang=false" class="absolute right-0 mt-2 w-28 bg-white text-gray-900 border border-white/30 rounded-md shadow z-50">
                        <button @click="$store.ui.setLang('en'); openLang=false" class="block w-full text-left px-3 py-2 text-sm hover:bg-gray-100">EN</button>
                        <button @click="$store.ui.setLang('sw'); openLang=false" class="block w-full text-left px-3 py-2 text-sm hover:bg-gray-100">SW</button>
                    </div>
                </div>

                <!-- Theme toggle -->
                <button @click="$store.ui.toggleTheme()" class="p-2 rounded-md border border-white/30 text-white/90 hover:bg-white/10" :aria-label="'Toggle '+($store.ui.theme==='dark'?'light':'dark')+' mode'">
                    <svg x-show="$store.ui.theme==='light'" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18a6 6 0 110-12 6 6 0 010 12zm0 4a1 1 0 011 1h-2a1 1 0 011-1zm0-22a1 1 0 01-1-1h2a1 1 0 01-1 1zM1 13a1 1 0 110-2h2a1 1 0 110 2H1zm20 0a1 1 0 110-2h2a1 1 0 110 2h-2zM4.222 18.364a1 1 0 011.414 0l1.414 1.414a1 1 0 11-1.414 1.414L4.222 19.778a1 1 0 010-1.414zM16.95 5.636a1 1 0 010-1.414L18.364 2.8a1 1 0 111.414 1.414L18.364 5.636a1 1 0 01-1.414 0zM18.364 19.778a1 1 0 011.414 0l1.414 1.414A1 1 0 0119.778 22.6l-1.414-1.414a1 1 0 010-1.414zM5.636 4.222A1 1 0 014.222 2.8L2.808 1.384A1 1 0 114.222-.03L5.636 1.384a1 1 0 010 1.414z"/></svg>
                    <svg x-show="$store.ui.theme==='dark'" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M21.64 13.64A9 9 0 1110.36 2.36 7 7 0 0021.64 13.64z"/></svg>
                </button>
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
    <div x-show="open" class="md:hidden border-t border-primary-800 bg-primary-900 text-white">
        <div class="px-4 py-3 space-y-2">
            <a href="#home" class="block text-white/90 hover:text-white"><span x-text="$store.ui.t('home')">Home</span></a>
            <a href="#regions" class="block text-white/90 hover:text-white"><span x-text="$store.ui.t('regions')">Regions</span></a>
            <a href="#about" class="block text-white/90 hover:text-white"><span x-text="$store.ui.t('about')">About</span></a>
            <a href="#contact" class="block text-white/90 hover:text-white"><span x-text="$store.ui.t('contact')">Contact</span></a>
            <a href="#faq" class="block text-white/90 hover:text-white"><span x-text="$store.ui.t('faq')">FAQ</span></a>
            <div class="pt-2 border-t border-primary-800">
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-md bg-white/10 hover:bg-white/20 text-white font-semibold"><span x-text="$store.ui.t('dashboard')">Dashboard</span></a>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2 rounded-md border border-white/30 text-white/90 hover:bg-white/10"><span x-text="$store.ui.t('login')">Login</span></a>
                    <a href="{{ route('register') }}" class="block mt-2 px-4 py-2 rounded-md bg-white text-primary-900 font-semibold"><span x-text="$store.ui.t('get_started')">Get Started</span></a>
                @endauth
                <div class="flex items-center gap-2 mt-3">
                    <button @click="$store.ui.toggleTheme()" class="px-3 py-2 rounded-md border border-white/30 text-white/90 text-sm">Toggle Theme</button>
                    <button @click="$store.ui.setLang($store.ui.lang==='en'?'sw':'en')" class="px-3 py-2 rounded-md border border-white/30 text-white/90 text-sm" x-text="$store.ui.lang.toUpperCase()">EN</button>
                </div>
            </div>
        </div>
    </div>
</header>
