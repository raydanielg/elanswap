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
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                        </svg>
                        <span>{{ __('Dashboard') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('destinations.index')" :active="request()->routeIs('destinations.*')" class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ __('Destinations') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('applications.index')" :active="request()->routeIs('applications.*')" class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>{{ __('Applications') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('requests.index')" :active="request()->routeIs('requests.*')" class="flex items-center space-x-2" data-requires-payment>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h8M7 16h6"></path>
                        </svg>
                        <span>{{ __('My Requests') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('blog.index')" :active="request()->routeIs('blog.*')" class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span>{{ __('Blog') }}</span>
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Side Of Navbar -->
            <div class="hidden md:flex items-center space-x-4">
                <!-- Announcements Notification -->
                @php
                    $__announcements = \App\Models\Feature::active()->orderBy('sort_order')->orderBy('id')->get();
                    $__ann_count = $__announcements->count();
                    $__ann_latest = optional($__announcements->max('updated_at'))?->timestamp ?? 0;
                    $__user_id = auth()->id();
                @endphp
                <script>window.__ANN_LATEST_ID = @json($__announcements->max('id'));</script>
                <div class="relative" x-data="annc({ count: {{ $__ann_count }}, latest: {{ $__ann_latest }}, userId: {{ $__user_id ?? '0' }} })">
                    <button @click="toggle()" class="relative p-2 rounded-full text-blue-200 hover:text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <span class="sr-only">View announcements</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <!-- Yellow badge (positioned above icon) -->
                        <span x-show="unreadCount > 0" x-text="unreadCount"
                              class="absolute -top-1 -right-1 sm:-top-2 sm:-right-2 min-w-[14px] h-[14px] sm:min-w-[18px] sm:h-[18px] px-1 text-[10px] sm:text-[11px] leading-[14px] sm:leading-[18px] text-black bg-yellow-400 rounded-full text-center font-semibold"></span>
                    </button>
                    <!-- Desktop Centered Modal -->
                    <div x-show="open" x-transition.opacity class="fixed inset-0 z-40 hidden md:block bg-black/50 backdrop-blur-sm" @click="markRead()"></div>
                    <div x-show="open" x-transition.opacity.scale.90 class="fixed inset-0 z-50 hidden md:flex items-center justify-center" aria-modal="true" role="dialog" aria-labelledby="annc-title" @keydown.window.escape.prevent="markRead()">
                        <div class="w-full max-w-lg bg-white rounded-xl shadow-2xl ring-1 ring-black/10 overflow-hidden" @click.stop tabindex="-1">
                            <div class="px-5 py-4 border-b flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-primary-50 text-primary-700 ring-1 ring-primary-100">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a7 7 0 00-7 7v3.586l-1.707 1.707A1 1 0 004 16h16a1 1 0 00.707-1.707L19 12.586V9a7 7 0 00-7-7zm0 20a3 3 0 003-3H9a3 3 0 003 3z"/></svg>
                                    </span>
                                    <div class="flex flex-col">
                                        <span id="annc-title" class="text-sm font-semibold text-gray-900">Notifications</span>
                                        <span class="text-[11px] text-gray-500">Updated just now</span>
                                    </div>
                                </div>
                                <button @click="markRead()" class="p-2 rounded-md hover:bg-gray-100 text-gray-600" aria-label="Close">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M6.225 4.811L4.81 6.225 10.586 12l-5.775 5.775 1.414 1.414L12 13.414l5.775 5.775 1.414-1.414L13.414 12l5.775-5.775-1.414-1.414L12 10.586 6.225 4.811z"/></svg>
                                </button>
                            </div>
                            <div class="p-5 space-y-4">
                                <div class="flex items-center gap-2 text-sm text-gray-800">
                                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-green-50 text-green-700 ring-1 ring-green-200">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22a10 10 0 1110-10 10.011 10.011 0 01-10 10zm-1-6l7-7-1.414-1.414L11 13.172l-3.586-3.586L6 11z"/></svg>
                                    </span>
                                    <span class="font-medium">Notifications updated</span>
                                </div>
                                <div class="max-h-72 overflow-auto border rounded-lg divide-y">
                                    @forelse($__announcements as $item)
                                        <div class="p-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $item->title }}</div>
                                            <div class="mt-0.5 text-sm text-gray-600 leading-relaxed">{{ $item->description }}</div>
                                        </div>
                                    @empty
                                        <div class="p-6 text-center text-sm text-gray-500">No announcements</div>
                                    @endforelse
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div class="text-xs text-gray-500">How do you feel about the latest update?</div>
                                    <div class="flex items-center gap-2">
                                        <button @click="react('like')" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-50 text-gray-800 ring-1 ring-gray-200 hover:bg-gray-100 text-sm">
                                            <svg class="w-4 h-4 text-green-600" viewBox="0 0 24 24" fill="currentColor"><path d="M2 21h4V9H2v12zM22 9c0-1.103-.897-2-2-2h-5.586l.293-1.293.007-.053c0-.256-.098-.512-.293-.707l-1-1-4.707 4.707A.996.996 0 008 8v12h10a2 2 0 001.789-1.106l2-4A2 2 0 0022 14v-5z"/></svg>
                                            Like
                                        </button>
                                        <button @click="react('dislike')" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-50 text-gray-800 ring-1 ring-gray-200 hover:bg-gray-100 text-sm">
                                            <svg class="w-4 h-4 text-rose-600" viewBox="0 0 24 24" fill="currentColor"><path d="M22 3h-4v12h4V3zM2 15c0 1.103.897 2 2 2h5.586l-.293 1.293-.007.053c0 .256.098.512.293.707l1 1 4.707-4.707A.996.996 0 0016 15V3H6a2 2 0 00-1.789 1.106l-2 4A2 2 0 002 9v6z"/></svg>
                                            Dislike
                                        </button>
                                    </div>
                                </div>
                                <div x-show="toast" x-transition.opacity class="text-center text-xs text-green-700 bg-green-50 ring-1 ring-green-200 rounded px-3 py-2">Thanks! Feedback saved.</div>
                                <div class="pt-1 text-[11px] text-gray-400 text-center">Your feedback helps us improve ElanSwap updates for everyone.</div>
                            </div>
                        </div>
                    </div>
                    <!-- Mobile Modal Overlay (centered) -->
                    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/50 z-40 md:hidden" @click="markRead()"></div>
                    <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center md:hidden">
                        <div class="w-[92vw] max-w-sm max-h-[80vh] overflow-auto bg-white rounded-lg shadow-xl ring-1 ring-black/10" @click.stop>
                            <div class="px-4 py-3 border-b flex items-center justify-between sticky top-0 bg-white">
                                <span class="text-sm font-semibold text-gray-800">Announcements</span>
                                <button @click="markRead()" class="text-xs text-primary-600 hover:underline">Close & mark read</button>
                            </div>
                            <div class="py-1">
                                @forelse($__announcements as $item)
                                    <div class="px-4 py-3 hover:bg-gray-50">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->title }}</div>
                                        <div class="text-sm text-gray-600">{{ $item->description }}</div>
                                    </div>
                                @empty
                                    <div class="px-4 py-6 text-center text-sm text-gray-500">No announcements</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

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
                         class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" 
                         role="menu" 
                         aria-orientation="vertical" 
                         aria-labelledby="user-menu">
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                            <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            My Profile
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                            <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Settings
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                            <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            Change Password
                        </a>
                        <div class="border-t border-gray-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile actions: notifications + menu button -->
            <div class="pr-2 flex items-center md:hidden space-x-2">
                @php
                    $__announcements = \App\Models\Feature::active()->orderBy('sort_order')->orderBy('id')->get();
                    $__ann_count = $__announcements->count();
                    $__ann_latest = optional($__announcements->max('updated_at'))?->timestamp ?? 0;
                    $__user_id = auth()->id();
                @endphp
                <!-- Mobile Announcements Bell -->
                <div class="relative" x-data="annc({ count: {{ $__ann_count }}, latest: {{ $__ann_latest }}, userId: {{ $__user_id ?? '0' }} })">
                    <button @click="toggle()" class="relative p-2 rounded-full text-blue-200 hover:text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <span class="sr-only">View announcements</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span x-show="unreadCount > 0" x-text="unreadCount"
                              class="absolute -top-2 -right-2 min-w-[14px] h-[14px] sm:min-w-[18px] sm:h-[18px] px-1 text-[10px] sm:text-[11px] leading-[14px] sm:leading-[18px] text-black bg-yellow-400 rounded-full text-center font-semibold"></span>
                    </button>
                    <!-- Mobile Modal Overlay (centered) -->
                    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/50 z-40 md:hidden" @click="markRead()"></div>
                    <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center md:hidden">
                        <div class="w-[92vw] max-w-sm max-h-[80vh] overflow-auto bg-white rounded-lg shadow-xl ring-1 ring-black/10" @click.stop>
                            <div class="px-4 py-3 border-b flex items-center justify-between sticky top-0 bg-white">
                                <span class="text-sm font-semibold text-gray-800">Announcements</span>
                                <button @click="markRead()" class="text-xs text-primary-600 hover:underline">Close & mark read</button>
                            </div>
                            <div class="py-1">
                                @forelse($__announcements as $item)
                                    <div class="px-4 py-3 hover:bg-gray-50">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->title }}</div>
                                        <div class="text-sm text-gray-600">{{ $item->description }}</div>
                                    </div>
                                @empty
                                    <div class="px-4 py-6 text-center text-sm text-gray-500">No announcements</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu button -->
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
            <a href="{{ route('dashboard') }}" class="text-white hover:bg-primary-700 flex items-center px-3 py-2 rounded-md text-base font-medium">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('destinations.index') }}" class="text-blue-200 hover:bg-primary-700 hover:text-white flex items-center px-3 py-2 rounded-md text-base font-medium">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Destinations
            </a>
            <a href="{{ route('applications.index') }}" class="text-blue-200 hover:bg-primary-700 hover:text-white flex items-center px-3 py-2 rounded-md text-base font-medium">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Applications
            </a>
            <a href="{{ route('requests.index') }}" class="text-blue-200 hover:bg-primary-700 hover:text-white flex items-center px-3 py-2 rounded-md text-base font-medium" data-requires-payment>
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h8M7 16h6"></path>
                </svg>
                My Requests
            </a>
            <a href="{{ route('blog.index') }}" class="text-blue-200 hover:bg-primary-700 hover:text-white flex items-center px-3 py-2 rounded-md text-base font-medium">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                Blog
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
                <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-primary-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    My Profile
                </a>
                <a href="#" class="flex items-center px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-primary-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Settings
                </a>
                <a href="#" class="flex items-center px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-primary-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    Change Password
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-primary-700">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    // Alpine component for announcements notification
    function annc({ count = 0, latest = 0, userId = 0 }) {
        const key = `ann_read_${userId || 'guest'}`;
        const stored = Number(localStorage.getItem(key) || 0);
        const hasUnread = latest > stored;
        return {
            open: false,
            unreadCount: hasUnread ? count : 0,
            toast: false,
            toggle() {
                this.open = !this.open;
                if (!this.open) this.markRead();
            },
            markRead() {
                if (this.unreadCount > 0) {
                    localStorage.setItem(key, String(latest || Date.now()));
                    this.unreadCount = 0;
                }
                this.open = false;
            },
            async react(type) {
                try {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    // Use the most recent feature id if available (server rendered)
                    const featureId = (window.__ANN_LATEST_ID || null);
                    const res = await fetch("{{ route('announcements.feedback') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                        credentials: 'same-origin',
                        body: JSON.stringify({ reaction: type, feature_id: featureId })
                    });
                    if (res.ok) {
                        this.toast = true;
                        setTimeout(()=> this.toast = false, 2000);
                    }
                } catch (e) { /* ignore */ }
            }
        }
    }
    window.annc = annc;
    document.addEventListener('alpine:init', () => {
        // In case using Alpine.data pattern in future
        if (window.Alpine && !Alpine.data('annc')) {
            Alpine.data('annc', annc);
        }
    });
;</script>
