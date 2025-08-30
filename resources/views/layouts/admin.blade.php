<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Elan Swap') }} Admin</title>
        <link rel="icon" href="{{ asset('logo.png') }}" type="image/png" sizes="any">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <!-- Material Symbols (Google Icons) -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0" />

        <!-- Styles & Scripts (no Vite) -->
        <link rel="stylesheet" href="{{ asset('assets/app.css') }}" />
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 h-full">
        <div class="min-h-screen flex flex-col" x-data="{ sidebarOpen: false }">
            {{-- Minimal Admin Header (no main menus) --}}
            <header class="bg-primary-900 shadow-lg sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <div class="flex items-center">
                        <a href="{{ url('/admin') }}" class="flex items-center text-white">
                        <img src="{{ asset('logo.png') }}" alt="Elan Swap" class="w-8 h-8 mr-2 rounded" />
                        <span class="text-lg font-semibold">Elan Swap Admin</span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Mobile sidebar toggle on right -->
                        <button @click="sidebarOpen = true" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-blue-200 hover:text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white" aria-label="Open sidebar">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>
                        <!-- User dropdown -->
                        <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
                            <button @click="open = !open" class="flex items-center gap-2 rounded-md px-2.5 py-1.5 text-blue-200 hover:text-white hover:bg-primary-800 focus:outline-none">
                                <div class="h-8 w-8 rounded-full bg-white/10 flex items-center justify-center text-white text-sm font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="text-left hidden sm:block">
                                    <div class="text-sm leading-4 font-medium">{{ Auth::user()->name ?? '' }}</div>
                                    <div class="text-[11px] text-blue-300 -mt-0.5">{{ Auth::user()->email ?? '' }}</div>
                                </div>
                                <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition @click.outside="open = false" class="absolute right-0 mt-2 w-56 bg-white text-gray-700 rounded-md shadow-lg ring-1 ring-black/5 z-50">
                                <div class="py-1">
                                    <a href="{{ route('admin.profile') }}" class="flex items-center gap-2 px-3 py-2 text-sm hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14c-3.866 0-7 2.239-7 5v1h14v-1c0-2.761-3.134-5-7-5zm0-2a3 3 0 100-6 3 3 0 000 6z"/></svg>
                                        <span>My Profile</span>
                                    </a>
                                    <a href="{{ route('admin.settings.general') }}" class="flex items-center gap-2 px-3 py-2 text-sm hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317a1 1 0 011.35-.936 9.042 9.042 0 015.657 5.657 1 1 0 01-.936 1.35l-.89.178a7.003 7.003 0 01-.516 1.18l.513.513a1 1 0 010 1.414l-1.414 1.414a1 1 0 01-1.414 0l-.513-.513a7.003 7.003 0 01-1.18.516l-.178.89z"/></svg>
                                        <span>Settings</span>
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm hover:bg-gray-50 text-red-600">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H7"/></svg>
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Admin Shell: Sidebar + Content -->
            <div class="flex-1 flex overflow-hidden">
                <!-- Mobile overlay -->
                <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-x-0 bottom-0 top-16 bg-black/50 z-50 md:hidden" @click="sidebarOpen = false"></div>
                <!-- Mobile Sidebar Drawer -->
                <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="-translate-x-full opacity-0" class="fixed top-16 left-0 h-[calc(100dvh-4rem)] z-60 w-72 bg-primary-900 text-white shadow-lg overflow-y-auto overscroll-contain md:hidden">
                    <div class="h-full flex flex-col">
                        <div class="px-4 py-4 border-b border-primary-800 flex items-center justify-between">
                            <h2 class="text-sm font-semibold uppercase tracking-wide">Admin</h2>
                            <button @click="sidebarOpen = false" class="p-2 rounded-md text-blue-200 hover:text-white hover:bg-primary-800" aria-label="Close sidebar">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <nav class="flex-1 px-2 py-4 space-y-1">
                            <!-- Dashboard -->
                            <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-primary-800 text-white' : 'text-blue-100 hover:bg-primary-800 hover:text-white' }}">
                                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" /></svg>
                                Dashboard
                            </a>
                            <!-- Announcements -->
                            <a href="{{ route('admin.features.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.features.*') ? 'bg-primary-800 text-white' : 'text-blue-100 hover:bg-primary-800 hover:text-white' }}">
                                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.features.*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" /></svg>
                                Announcements
                            </a>
                            <!-- Requests (group) -->
                            <div x-data="{ open: {{ request()->is('admin/requests*') ? 'true' : 'false' }} }" class="text-blue-100">
                                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-primary-800">
                                    <span class="flex items-center">
                                        <svg class="mr-3 h-5 w-5 {{ request()->is('admin/requests*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 19h14M5 15h14" /></svg>
                                        <span class="text-sm font-medium {{ request()->is('admin/requests*') ? 'text-white' : '' }}">Requests</span>
                                    </span>
                                    <svg class="h-4 w-4 transition-transform" :class="open ? 'transform rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-1">
                                    <ol class="space-y-1">
                                        <li>
                                            <a href="{{ url('/admin/requests') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/requests') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h8m-6 4h6M5 5h14v14H5z"/></svg>
                                                <span>All Requests</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.requests.index', ['status' => 'accepted']) }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ (request('status')==='accepted' && request()->is('admin/requests')) ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span>Approved</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.requests.index', ['status' => 'rejected']) }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ (request('status')==='rejected' && request()->is('admin/requests')) ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span>Rejected</span>
                                            </a>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <!-- Applications (group) -->
                            <div x-data="{ open: {{ request()->is('admin/applications*') ? 'true' : 'false' }} }" class="text-blue-100">
                                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-primary-800">
                                    <span class="flex items-center">
                                        <svg class="mr-3 h-5 w-5 {{ request()->is('admin/applications*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m2 2a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                        <span class="text-sm font-medium {{ request()->is('admin/applications*') ? 'text-white' : '' }}">Applications</span>
                                    </span>
                                    <svg class="h-4 w-4 transition-transform" :class="open ? 'transform rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-1">
                                    <ol class="space-y-1">
                                        <li>
                                            <a href="{{ url('/admin/applications') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/applications') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-8 4h8M7 8h10M5 5h14v14H5z"/></svg>
                                                <span>All Applications</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/applications/successful') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/applications/successful') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span>Successfully</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/applications/rejected') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/applications/rejected') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span>Rejected</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/applications/request-delete') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/applications/request-delete') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-3h4m-6 3h8m-9 0h10"/></svg>
                                                <span>Request Delete</span>
                                            </a>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <!-- User Management (group) -->
                            <div x-data="{ open: {{ request()->is('admin/users*') ? 'true' : 'false' }} }" class="text-blue-100">
                                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-primary-800">
                                    <span class="flex items-center">
                                        <svg class="mr-3 h-5 w-5 {{ request()->is('admin/users*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0 0a4 4 0 100-8 4 4 0 000 8zm10 0a4 4 0 10-8 0" /></svg>
                                        <span class="text-sm font-medium {{ request()->is('admin/users*') ? 'text-white' : '' }}">User Management</span>
                                    </span>
                                    <svg class="h-4 w-4 transition-transform" :class="open ? 'transform rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-1">
                                    <ol class="space-y-1">
                                        <li>
                                            <a href="{{ url('/admin/users') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/users') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m6-6a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                                <span>All Users</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.profile') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->routeIs('admin.profile') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14c-3.866 0-7 2.239-7 5v1h14v-1c0-2.761-3.134-5-7-5zm0-2a3 3 0 100-6 3 3 0 000 6z"/></svg>
                                                <span>My Admin Profile</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.users.index', ['banned' => 'yes']) }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ (request()->is('admin/users') && request('banned')==='yes') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-12.728 12.728M6 6l12 12M6 18a6 6 0 1112 0 6 6 0 01-12 0z"/></svg>
                                                <span>Banned</span>
                                            </a>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <!-- Locations (group) -->
                            <div x-data="{ open: {{ request()->is('admin/locations*') ? 'true' : 'false' }} }" class="text-blue-100">
                                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-primary-800">
                                    <span class="flex items-center">
                                        <svg class="mr-3 h-5 w-5 {{ request()->is('admin/locations*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm0 0c-4 0-7 2-7 4v2h14v-2c0-2-3-4-7-4z" /></svg>
                                        <span class="text-sm font-medium {{ request()->is('admin/locations*') ? 'text-white' : '' }}">Locations</span>
                                    </span>
                                    <svg class="h-4 w-4 transition-transform" :class="open ? 'transform rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-1">
                                    <ol class="space-y-1">
                                        <li>
                                            <a href="{{ url('/admin/locations') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/locations') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm0 2c-4 0-7 2-7 4v2h14v-2c0-2-3-4-7-4z"/></svg>
                                                <span>All Locations</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/locations/regions') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/locations/regions') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                                <span>Regions</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/locations/districts') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/locations/districts') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-purple-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 12.414m0 0A5 5 0 1112.414 11l4.243 4.243z"/></svg>
                                                <span>Wilaya</span>
                                            </a>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <!-- Blog (group) -->
                            <div x-data="{ open: {{ request()->routeIs('admin.blog.*') ? 'true' : 'false' }} }" class="text-blue-100">
                                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-primary-800">
                                    <span class="flex items-center">
                                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.blog.*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12l9-5-9-5-9 5 9 5z" /></svg>
                                        <span class="text-sm font-medium {{ request()->routeIs('admin.blog.*') ? 'text-white' : '' }}">Blog</span>
                                    </span>
                                    <svg class="h-4 w-4 transition-transform" :class="open ? 'transform rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-1">
                                    <ol class="space-y-1">
                                        <li>
                                            <a href="{{ route('admin.blog.index') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->routeIs('admin.blog.index') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V7h18v12a2 2 0 01-2 2zM7 7V5a2 2 0 012-2h6a2 2 0 012 2v2"/></svg>
                                                <span>All Blog</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/blog/manage') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/blog/manage') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m-4-4h8M4 6h16"/></svg>
                                                <span>Manage</span>
                                            </a>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <!-- Profile (group) -->
                            <div x-data="{ open: {{ (request()->routeIs('admin.profile') || request()->routeIs('admin.profile.password')) ? 'true' : 'false' }} }" class="text-blue-100">
                                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-primary-800">
                                    <span class="flex items-center">
                                        <svg class="mr-3 h-5 w-5 {{ (request()->routeIs('admin.profile') || request()->routeIs('admin.profile.password')) ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14c-3.866 0-7 2.239-7 5v1h14v-1c0-2.761-3.134-5-7-5zm0-2a3 3 0 100-6 3 3 0 000 6z" /></svg>
                                        <span class="text-sm font-medium {{ (request()->routeIs('admin.profile') || request()->routeIs('admin.profile.password')) ? 'text-white' : '' }}">Profile</span>
                                    </span>
                                    <svg class="h-4 w-4 transition-transform" :class="open ? 'transform rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-1">
                                    <ol class="space-y-1">
                                        <li>
                                            <a href="{{ route('admin.profile') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->routeIs('admin.profile') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14c-3.866 0-7 2.239-7 5v1h14v-1c0-2.761-3.134-5-7-5zm0-2a3 3 0 100-6 3 3 0 000 6z"/></svg>
                                                <span>My Details</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/profile/password') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->routeIs('admin.profile') ? '' : '' }} {{ request()->is('profile/password') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3V6a3 3 0 10-6 0v2c0 1.657 1.343 3 3 3zm0 2a5 5 0 00-5 5v1h10v-1a5 5 0 00-5-5z"/></svg>
                                                <span>Change Password</span>
                                            </a>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <!-- Settings (group) -->
                            <div x-data="{ open: {{ request()->is('admin/settings*') ? 'true' : 'false' }} }" class="text-blue-100">
                                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-primary-800">
                                    <span class="flex items-center">
                                        <svg class="mr-3 h-5 w-5 {{ request()->is('admin/settings*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317a1 1 0 011.35-.936 9.042 9.042 0 015.657 5.657 1 1 0 01-.936 1.35l-.89.178a7.003 7.003 0 01-.516 1.18l.513.513a1 1 0 010 1.414l-1.414 1.414a1 1 0 01-1.414 0l-.513-.513a7.003 7.003 0 01-1.18.516l-.178.89z" /></svg>
                                        <span class="text-sm font-medium {{ request()->is('admin/settings*') ? 'text-white' : '' }}">Settings</span>
                                    </span>
                                    <svg class="h-4 w-4 transition-transform" :class="open ? 'transform rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-1">
                                    <ol class="space-y-1">
                                        <li>
                                            <a href="{{ route('admin.settings.general') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->routeIs('admin.settings.general') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317a1 1 0 011.35-.936 9.042 9.042 0 015.657 5.657 1 1 0 01-.936 1.35l-.89.178a7.003 7.003 0 01-.516 1.18l.513.513a1 1 0 010 1.414l-1.414 1.414a1 1 0 01-1.414 0l-.513-.513a7.003 7.003 0 01-1.18.516l-.178.89z"/></svg>
                                                <span>General</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.settings.email') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->routeIs('admin.settings.email') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-teal-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18V8H3v8z"/></svg>
                                                <span>Mail</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.settings.site') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->routeIs('admin.settings.site') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M5 10h14M7 13h10M9 16h6"/></svg>
                                                <span>Site Management</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.settings.other') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->routeIs('admin.settings.other') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-yellow-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/></svg>
                                                <span>Other Settings</span>
                                            </a>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </nav>
                        <!-- Mobile Sidebar Footer: User dropup -->
                        <div class="mt-auto border-t border-primary-800 p-4" x-data="{ open: false }" @keydown.escape.window="open = false">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full bg-primary-700 flex items-center justify-center text-white text-sm font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? '' }}</p>
                                    <p class="text-xs text-blue-200 truncate">{{ Auth::user()->email ?? '' }}</p>
                                </div>
                                <div class="relative">
                                    <button @click="open = !open" class="p-2 rounded-md text-blue-200 hover:text-white hover:bg-primary-800" aria-haspopup="true" :aria-expanded="open.toString()" aria-label="Toggle account menu">
                                        <svg class="h-5 w-5 transition-transform" :class="open ? 'transform rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </button>
                                    <div x-show="open" @click.outside="open = false" x-transition.origin.bottom class="absolute right-0 bottom-12 mb-2 w-52 bg-white text-gray-700 rounded-md shadow-lg ring-1 ring-black/5 z-50">
                                        <a href="{{ route('admin.profile') }}" @click="open = false" class="block px-4 py-2 text-sm hover:bg-gray-100">My Profile</a>
                                        <form method="POST" action="{{ route('logout') }}" @submit="open = false">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">Logout</button>
                                        </form>
                                        <a href="{{ url('/admin/profile/password') }}" @click="open = false" class="block px-4 py-2 text-sm hover:bg-gray-100">Change Password</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Desktop Sidebar -->
                <aside class="w-64 bg-primary-900 text-white border-r border-primary-800 hidden md:block">
                    <div class="h-full flex flex-col">
                        <div class="px-4 py-4 border-b border-primary-800">
                            <h2 class="text-sm font-semibold uppercase tracking-wide">Admin</h2>
                        </div>
                        <nav class="flex-1 px-2 py-4 space-y-1">
                            <!-- Dashboard -->
                            <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-primary-800 text-white' : 'text-blue-100 hover:bg-primary-800 hover:text-white' }}">
                                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" /></svg>
                                Dashboard
                            </a>
                            <!-- Announcements -->
                            <a href="{{ route('admin.features.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.features.*') ? 'bg-primary-800 text-white' : 'text-blue-100 hover:bg-primary-800 hover:text-white' }}">
                                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.features.*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" /></svg>
                                Announcements
                            </a>
                            <!-- Requests (group) -->
                            <div x-data="{ open: {{ request()->is('admin/requests*') ? 'true' : 'false' }} }" class="text-blue-100">
                                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-primary-800">
                                    <span class="flex items-center">
                                        <svg class="mr-3 h-5 w-5 {{ request()->is('admin/requests*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 19h14M5 15h14" /></svg>
                                        <span class="text-sm font-medium {{ request()->is('admin/requests*') ? 'text-white' : '' }}">Requests</span>
                                    </span>
                                    <svg class="h-4 w-4 transition-transform" :class="open ? 'transform rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-1">
                                    <ol class="space-y-1">
                                        <li>
                                            <a href="{{ url('/admin/requests') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/requests') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h8m-6 4h6M5 5h14v14H5z"/></svg>
                                                <span>All Requests</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/requests/approved') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/requests/approved') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span>Approved</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/requests/rejected') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/requests/rejected') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span>Rejected</span>
                                            </a>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <!-- Applications (group) -->
                            <div x-data="{ open: {{ request()->is('admin/applications*') ? 'true' : 'false' }} }" class="text-blue-100">
                                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-primary-800">
                                    <span class="flex items-center">
                                        <svg class="mr-3 h-5 w-5 {{ request()->is('admin/applications*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m2 2a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                        <span class="text-sm font-medium {{ request()->is('admin/applications*') ? 'text-white' : '' }}">Applications</span>
                                    </span>
                                    <svg class="h-4 w-4 transition-transform" :class="open ? 'transform rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-1">
                                    <ol class="space-y-1">
                                        <li>
                                            <a href="{{ url('/admin/applications') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/applications') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-8 4h8M7 8h10M5 5h14v14H5z"/></svg>
                                                <span>All Applications</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/applications/successful') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/applications/successful') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span>Successfully</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/applications/rejected') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/applications/rejected') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span>Rejected</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/applications/request-delete') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/applications/request-delete') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-3h4m-6 3h8m-9 0h10"/></svg>
                                                <span>Request Delete</span>
                                            </a>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <!-- User Management (group) -->
                            <div x-data="{ open: {{ request()->is('admin/users*') ? 'true' : 'false' }} }" class="text-blue-100">
                                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-primary-800">
                                    <span class="flex items-center">
                                        <svg class="mr-3 h-5 w-5 {{ request()->is('admin/users*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0 0a4 4 0 100-8 4 4 0 000 8zm10 0a4 4 0 10-8 0" /></svg>
                                        <span class="text-sm font-medium {{ request()->is('admin/users*') ? 'text-white' : '' }}">User Management</span>
                                    </span>
                                    <svg class="h-4 w-4 transition-transform" :class="open ? 'transform rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-1">
                                    <ol class="space-y-1">
                                        <li>
                                            <a href="{{ url('/admin/users') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/users') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m6-6a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                                <span>All Users</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.users.profile', Auth::user()) }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->routeIs('admin.users.profile') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14c-3.866 0-7 2.239-7 5v1h14v-1c0-2.761-3.134-5-7-5zm0-2a3 3 0 100-6 3 3 0 000 6z"/></svg>
                                                <span>My Admin Profile</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/users/banned') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/users/banned') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-12.728 12.728M6 6l12 12M6 18a6 6 0 1112 0 6 6 0 01-12 0z"/></svg>
                                                <span>Banned</span>
                                            </a>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <!-- Locations (group) -->
                            <div x-data="{ open: {{ request()->is('admin/locations*') ? 'true' : 'false' }} }" class="text-blue-100">
                                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-primary-800">
                                    <span class="flex items-center">
                                        <svg class="mr-3 h-5 w-5 {{ request()->is('admin/locations*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm0 0c-4 0-7 2-7 4v2h14v-2c0-2-3-4-7-4z" /></svg>
                                        <span class="text-sm font-medium {{ request()->is('admin/locations*') ? 'text-white' : '' }}">Locations</span>
                                    </span>
                                    <svg class="h-4 w-4 transition-transform" :class="open ? 'transform rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-1">
                                    <ol class="space-y-1">
                                        <li>
                                            <a href="{{ url('/admin/locations') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/locations') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm0 2c-4 0-7 2-7 4v2h14v-2c0-2-3-4-7-4z"/></svg>
                                                <span>All Locations</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/locations/regions') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/locations/regions') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                                <span>Regions</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/locations/districts') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/locations/districts') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-purple-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 12.414m0 0A5 5 0 1112.414 11l4.243 4.243z"/></svg>
                                                <span>Wilaya</span>
                                            </a>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <!-- Blog (group) -->
                            <div x-data="{ open: {{ request()->routeIs('admin.blog.*') ? 'true' : 'false' }} }" class="text-blue-100">
                                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-primary-800">
                                    <span class="flex items-center">
                                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.blog.*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12l9-5-9-5-9 5 9 5z" /></svg>
                                        <span class="text-sm font-medium {{ request()->routeIs('admin.blog.*') ? 'text-white' : '' }}">Blog</span>
                                    </span>
                                    <svg class="h-4 w-4 transition-transform" :class="open ? 'transform rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-1">
                                    <ol class="space-y-1">
                                        <li>
                                            <a href="{{ route('admin.blog.index') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->routeIs('admin.blog.index') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V7h18v12a2 2 0 01-2 2zM7 7V5a2 2 0 012-2h6a2 2 0 012 2v2"/></svg>
                                                <span>All Blog</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/blog/manage') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/blog/manage') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m-4-4h8M4 6h16"/></svg>
                                                <span>Manage</span>
                                            </a>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <!-- Profile (group) -->
                            <div x-data="{ open: {{ (request()->routeIs('admin.profile') || request()->routeIs('admin.profile.password')) ? 'true' : 'false' }} }" class="text-blue-100">
                                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-primary-800">
                                    <span class="flex items-center">
                                        <svg class="mr-3 h-5 w-5 {{ (request()->routeIs('admin.profile') || request()->routeIs('admin.profile.password')) ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14c-3.866 0-7 2.239-7 5v1h14v-1c0-2.761-3.134-5-7-5zm0-2a3 3 0 100-6 3 3 0 000 6z" /></svg>
                                        <span class="text-sm font-medium {{ (request()->routeIs('admin.profile') || request()->routeIs('admin.profile.password')) ? 'text-white' : '' }}">Profile</span>
                                    </span>
                                    <svg class="h-4 w-4 transition-transform" :class="open ? 'transform rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-1">
                                    <ol class="space-y-1">
                                        <li>
                                            <a href="{{ route('admin.profile') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->routeIs('admin.profile') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14c-3.866 0-7 2.239-7 5v1h14v-1c0-2.761-3.134-5-7-5zm0-2a3 3 0 100-6 3 3 0 000 6z"/></svg>
                                                <span>My Details</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.profile.password') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->routeIs('admin.profile.password') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3V6a3 3 0 10-6 0v2c0 1.657 1.343 3 3 3zm0 2a5 5 0 00-5 5v1h10v-1a5 5 0 00-5-5z"/></svg>
                                                <span>Change Password</span>
                                            </a>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <!-- Settings (group) -->
                            <div x-data="{ open: {{ request()->is('admin/settings*') ? 'true' : 'false' }} }" class="text-blue-100">
                                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-primary-800">
                                    <span class="flex items-center">
                                        <svg class="mr-3 h-5 w-5 {{ request()->is('admin/settings*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317a1 1 0 011.35-.936 9.042 9.042 0 015.657 5.657 1 1 0 01-.936 1.35l-.89.178a7.003 7.003 0 01-.516 1.18l.513.513a1 1 0 010 1.414l-1.414 1.414a1 1 0 01-1.414 0l-.513-.513a7.003 7.003 0 01-1.18.516l-.178.89z" /></svg>
                                        <span class="text-sm font-medium {{ request()->is('admin/settings*') ? 'text-white' : '' }}">Settings</span>
                                    </span>
                                    <svg class="h-4 w-4 transition-transform" :class="open ? 'transform rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-1">
                                    <ol class="space-y-1">
                                        <li>
                                            <a href="{{ url('/admin/settings') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/settings') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317a1 1 0 011.35-.936 9.042 9.042 0 015.657 5.657 1 1 0 01-.936 1.35l-.89.178a7.003 7.003 0 01-.516 1.18l.513.513a1 1 0 010 1.414l-1.414 1.414a1 1 0 01-1.414 0l-.513-.513a7.003 7.003 0 01-1.18.516l-.178.89z"/></svg>
                                                <span>General</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/settings/mail') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/settings/mail') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-teal-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18V8H3v8z"/></svg>
                                                <span>Mail</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/settings/site-management') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/settings/site-management') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M5 10h14M7 13h10M9 16h6"/></svg>
                                                <span>Site Management</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/settings/other') }}" class="flex items-center w-full px-2 py-1.5 text-sm rounded-md {{ request()->is('admin/settings/other') ? 'text-white bg-primary-800' : 'text-blue-100 hover:text-white hover:bg-primary-800' }}">
                                                <svg class="mr-2 h-4 w-4 text-yellow-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/></svg>
                                                <span>Other Settings</span>
                                            </a>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </nav>
                        <!-- Desktop Sidebar Footer: User dropup -->
                        <div class="mt-auto border-t border-primary-800 p-4" x-data="{ open: false }">
                            <div class="flex items-center space-x-3">
                                <div class="h-9 w-9 rounded-full bg-primary-700 flex items-center justify-center text-white text-sm font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? '' }}</p>
                                    <p class="text-xs text-blue-200 truncate">{{ Auth::user()->email ?? '' }}</p>
                                </div>
                                <div class="relative">
                                    <button @click="open = !open" class="p-2 rounded-md text-blue-200 hover:text-white hover:bg-primary-800" aria-haspopup="true" :aria-expanded="open.toString()" aria-label="Toggle account menu">
                                        <svg class="h-5 w-5 transition-transform" :class="open ? 'transform rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </button>
                                    <div x-show="open" @click.outside="open = false" x-transition.origin.bottom class="absolute right-0 bottom-12 mb-2 w-48 bg-white text-gray-700 rounded-md shadow-lg ring-1 ring-black/5 z-50">
                                        <a href="{{ route('admin.profile') }}" @click="open = false" class="block px-4 py-2 text-sm hover:bg-gray-100">My Profile</a>
                                        <form method="POST" action="{{ route('logout') }}" @submit="open = false">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">Logout</button>
                                        </form>
                                        <a href="{{ route('admin.profile.password') }}" @click="open = false" class="block px-4 py-2 text-sm hover:bg-gray-100">Change Password</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Main Content -->
                <main class="flex-1 overflow-y-auto bg-gradient-to-b from-white to-blue-50">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                        @isset($header)
                            <div class="mb-8">
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $header }}</h1>
                            </div>
                        @endisset

                        @hasSection('content')
                            @yield('content')
                        @else
                            {{ $slot ?? '' }}
                        @endif
                    </div>
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
