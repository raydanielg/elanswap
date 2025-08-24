<header class="fixed top-0 inset-x-0 z-30 bg-white/90 backdrop-blur border-b border-gray-200">
  <div class="w-full px-3 sm:px-4 lg:px-6 md:pl-64 h-14 flex items-center gap-4">
    <!-- Left: burger + brand -->
    <div class="flex items-center gap-3 shrink-0">
      <button class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:bg-gray-100" @click="sidebar = !sidebar">
        <span class="material-symbols-outlined">menu</span>
      </button>
      <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-2 font-semibold">
        <span class="inline-flex items-center justify-center w-8 h-8 rounded bg-primary-600 text-white">S</span>
        <span>Super Admin</span>
      </a>
    </div>

    <!-- Center: search -->
    <div class="flex-1 hidden sm:flex items-center">
      <form action="{{ url('/super/search') }}" method="GET" class="w-full flex justify-center">
        <label class="relative block">
          <span class="material-symbols-outlined absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">search</span>
          <input
            type="search"
            name="q"
            placeholder="Tafuta..."
            class="block w-[20rem] md:w-[28rem] rounded-md border border-gray-300 bg-white h-9 py-1.5 pl-10 pr-3 text-sm placeholder-gray-400 focus:border-primary-500 focus:ring-primary-500"
          />
        </label>
      </form>
    </div>

    <!-- Right: home + user dropdown -->
    <div class="flex items-center gap-2 ml-auto" x-data="{open:false}">
      <a href="{{ route('home.public') }}" class="hidden sm:inline-flex items-center gap-1 text-sm text-gray-600 hover:text-gray-900">
        <span class="material-symbols-outlined text-base">home</span>
        Home
      </a>

      <!-- User dropdown -->
      <button @click="open = !open" class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-md hover:bg-gray-100">
        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'SA') }}&background=0D8ABC&color=fff" alt="avatar" class="w-8 h-8 rounded-full">
        <span class="hidden sm:block text-sm text-gray-800">{{ auth()->user()->name ?? 'User' }}</span>
        <span class="material-symbols-outlined text-base">expand_more</span>
      </button>

      <div x-cloak x-show="open" @click.outside="open=false" x-transition
           class="absolute right-4 top-14 z-40 w-56 bg-white border border-gray-200 rounded-md shadow-sm overflow-hidden">
        <div class="px-4 py-3 text-sm">
          <div class="font-medium text-gray-900 truncate">{{ auth()->user()->name ?? '' }}</div>
          <div class="text-gray-500 truncate">{{ auth()->user()->email ?? auth()->user()->phone ?? '' }}</div>
        </div>
        <div class="py-1 text-sm text-gray-700">
          <a href="{{ url('/super/dashboard') }}" class="block px-4 py-2 hover:bg-gray-50">Dashboard</a>
          <a href="{{ url('/super/settings/general') }}" class="block px-4 py-2 hover:bg-gray-50">Settings</a>
          <a href="{{ url('/super/settings/api') }}" class="block px-4 py-2 hover:bg-gray-50">API</a>
        </div>
        <div class="border-t border-gray-200"></div>
        <div class="py-1">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Sign out</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</header>
