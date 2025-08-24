<nav x-data="{ open: false }" class="bg-primary-900 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <svg class="w-10 h-10 text-white mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xl font-bold text-white">ElanSwap</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:ml-10 md:flex md:space-x-8">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link href="#">
                        {{ __('Find Tutors') }}
                    </x-nav-link>
                    <x-nav-link href="#">
                        {{ __('My Lessons') }}
                    </x-nav-link>
                    <x-nav-link href="#">
                        {{ __('Messages') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Side Of Navbar -->
            <div class="hidden md:flex items-center space-x-4">
                <!-- Notification Bell -->
                <button class="p-2 rounded-full text-blue-200 hover:text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <span class="sr-only">View notifications</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </button>

                <!-- Profile dropdown -->
                <div class="ml-3 relative" x-data="{ open: false }">
                    <div>
                        <button @click="open = !open" class="flex items-center max-w-xs rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" id="user-menu" aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only">Open user menu</span>
                            <div class="h-10 w-10 rounded-full bg-primary-700 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="ml-3 text-white font-medium">{{ Auth::user()->name }}</span>
                            <svg class="ml-1 h-5 w-5 text-blue-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <!-- Dropdown menu -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" 
                         role="menu" 
                         aria-orientation="vertical" 
                         aria-labelledby="user-menu">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                            Your Profile
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                            Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="-mr-2 flex items-center md:hidden">
                <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-blue-200 hover:text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <!-- Icon when menu is closed -->
                    <svg :class="{'hidden': open, 'block': !open }" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Icon when menu is open -->
                    <svg :class="{'hidden': !open, 'block': open }" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state -->
    <div x-show="open" class="md:hidden bg-primary-800" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('dashboard') }}" class="text-white hover:bg-primary-700 block px-3 py-2 rounded-md text-base font-medium">
                Dashboard
            </a>
            <a href="#" class="text-blue-200 hover:bg-primary-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                Find Tutors
            </a>
            <a href="#" class="text-blue-200 hover:bg-primary-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                My Lessons
            </a>
            <a href="#" class="text-blue-200 hover:bg-primary-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                Messages
            </a>
        </div>
        <div class="pt-4 pb-3 border-t border-primary-700">
            <div class="flex items-center px-5">
                <div class="h-10 w-10 rounded-full bg-primary-600 flex items-center justify-center text-white font-semibold flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-blue-200">{{ Auth::user()->email }}</div>
                </div>
                <button class="ml-auto flex-shrink-0 p-1 rounded-full text-blue-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                    <span class="sr-only">View notifications</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </button>
            </div>
            <div class="mt-3 px-2 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-primary-700">
                    Your Profile
                </a>
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-primary-700">
                    Settings
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-primary-700">
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
