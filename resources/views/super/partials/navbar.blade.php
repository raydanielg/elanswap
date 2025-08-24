<header class="antialiased">
  <nav class="bg-white border-b border-gray-200 pl-3 pr-3 lg:pl-6 lg:pr-4 h-14 flex items-center dark:bg-gray-800">
    <div class="w-full flex items-center gap-3">
      <!-- Left: toggles + brand -->
      <div class="flex items-center gap-3 shrink-0">
        <!-- Desktop toggle -->
        <button aria-expanded="true" aria-controls="sidebar"
                class="hidden p-2 mr-3 text-gray-600 rounded cursor-pointer lg:inline hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700"
                @click="sidebar = !sidebar">
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h14M1 6h14M1 11h7"/>
          </svg>
        </button>
        <!-- Mobile toggle -->
        <button aria-expanded="true" aria-controls="sidebar"
                class="p-2 mr-2 text-gray-600 rounded-lg cursor-pointer lg:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                @click="sidebar = !sidebar">
          <svg class="w-[18px] h-[18px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
          </svg>
          <span class="sr-only">Toggle sidebar</span>
        </button>

        <!-- Brand (use site favicon as logo for now) -->
        <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-2">
          <img src="{{ asset('favicon.ico') }}" class="h-8 w-8" alt="Logo" />
          <span class="text-lg font-semibold whitespace-nowrap dark:text-white">{{ config('app.name', 'ElanSwap') }}</span>
        </a>
      </div>

      <!-- Middle: search (responsive) -->
      <div class="flex-1 hidden md:flex items-center justify-center">
        <form action="{{ url('/super/search') }}" method="GET" class="w-full max-w-lg">
          <label for="topbar-search" class="sr-only">Search</label>
          <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
              <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
              </svg>
            </div>
            <input type="search" name="q" id="topbar-search" placeholder="Search"
                   class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-9 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" />
          </div>
        </form>
      </div>

      <!-- Right: actions -->
      <div class="relative flex items-center gap-2 ml-auto" x-data="{open:false}">
        <button type="button" class="hidden sm:inline-flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-xs px-3 py-1.5 mr-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none"> 
          <svg aria-hidden="true" class="mr-1 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M10 5a1 1 0 0 1 1 1v3h3a1 1 0 1 1 0 2h-3v3a1 1 0 1 1-2 0v-3H6a1 1 0 1 1 0-2h3V6a1 1 0 0 1 1-1z" clip-rule="evenodd"></path>
          </svg>
          New Widget
        </button>

        <button type="button" class="p-2 text-gray-500 rounded-lg lg:hidden hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
          <span class="sr-only">Search</span>
          <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
          </svg>
        </button>

        <button type="button" class="p-2 mr-1 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700">
          <span class="sr-only">View notifications</span>
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 14 20"><path d="M12.133 10.632v-1.8A5.406 5.406 0 0 0 7.979 3.57.946.946 0 0 0 8 3.464V1.1a1 1 0 0 0-2 0v2.364a.946.946 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C1.867 13.018 0 13.614 0 14.807 0 15.4 0 16 .538 16h12.924C14 16 14 15.4 14 14.807c0-1.193-1.867-1.789-1.867-4.175ZM3.823 17a3.453 3.453 0 0 0 6.354 0H3.823Z"/></svg>
        </button>

        <!-- User dropdown (Alpine) -->
        <button @click="open = !open" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
          <span class="sr-only">Open user menu</span>
          <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'SA') }}&background=0D8ABC&color=fff" alt="user photo">
        </button>
        <div x-cloak x-show="open" @click.outside="open=false" x-transition
             class="absolute right-0 top-full mt-2 z-40 w-56 text-base list-none bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
          <div class="py-3 px-4">
            <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name ?? '' }}</span>
            <span class="block text-sm text-gray-500 truncate dark:text-gray-400">{{ auth()->user()->email ?? auth()->user()->phone ?? '' }}</span>
          </div>
          <ul class="py-1 text-gray-500 dark:text-gray-400">
            <li><a href="{{ url('/super/dashboard') }}" class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Dashboard</a></li>
            <li><a href="{{ url('/super/settings/general') }}" class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Settings</a></li>
            <li><a href="{{ url('/super/settings/api') }}" class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">API</a></li>
          </ul>
          <ul class="py-1 text-gray-500 dark:text-gray-400">
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Sign out</button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</header>
