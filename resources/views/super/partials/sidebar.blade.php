<aside
  class="w-64 border-r border-gray-200 bg-white overflow-y-auto min-h-screen"
>
  <nav class="p-3 space-y-2 text-sm">
    <!-- Dashboard / Muhtasari -->
    <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700 {{ request()->routeIs('superadmin.dashboard') ? 'bg-gray-100 font-medium' : '' }}">
      <span class="material-symbols-outlined">dashboard</span>
      <span>Dashboard</span>
    </a>
    <div class="ml-3 text-xs text-gray-500">Overview and analytics</div>

    <!-- User Management -->
    <div x-data="{open: false}" class="mt-1">
      <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
        <span class="inline-flex items-center gap-2"><span class="material-symbols-outlined">group</span> User Management</span>
        <span class="material-symbols-outlined text-base" x-text="open ? 'expand_less' : 'expand_more'"></span>
      </button>
      <div x-show="open" x-collapse class="mt-1 ml-2 pl-4 border-l border-gray-100 space-y-1">
        <a href="{{ url('/super/users') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">contacts</span>
          <span>Users List</span>
        </a>
        <a href="{{ url('/super/users/create') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">person_add</span>
          <span>Add User</span>
        </a>
        <a href="{{ url('/super/roles') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">admin_panel_settings</span>
          <span>Roles & Permissions</span>
        </a>
      </div>
    </div>

    <!-- Institutions / Organizations -->
    <div x-data="{open: false}" class="mt-1">
      <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
        <span class="inline-flex items-center gap-2"><span class="material-symbols-outlined">domain</span> Institutions</span>
        <span class="material-symbols-outlined text-base" x-text="open ? 'expand_less' : 'expand_more'"></span>
      </button>
      <div x-show="open" x-collapse class="mt-1 ml-2 pl-4 border-l border-gray-100 space-y-1">
        <a href="{{ url('/super/institutions') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">corporate_fare</span>
          <span>Organizations List</span>
        </a>
        <a href="{{ url('/super/institutions/create') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">playlist_add</span>
          <span>Register Organization</span>
        </a>
        <a href="{{ url('/super/stations') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">work</span>
          <span>Workstations</span>
        </a>
      </div>
    </div>

    <!-- Swap Requests -->
    <div x-data="{open: false}" class="mt-1">
      <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
        <span class="inline-flex items-center gap-2"><span class="material-symbols-outlined">swap_horiz</span> Swap Requests</span>
        <span class="material-symbols-outlined text-base" x-text="open ? 'expand_less' : 'expand_more'"></span>
      </button>
      <div x-show="open" x-collapse class="mt-1 ml-2 pl-4 border-l border-gray-100 space-y-1">
        <a href="{{ url('/super/swaps') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">list_alt</span>
          <span>Requests List</span>
        </a>
        <a href="{{ url('/super/swaps/pending') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">schedule</span>
          <span>Pending</span>
        </a>
        <a href="{{ url('/super/swaps/approved') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">check_circle</span>
          <span>Approved</span>
        </a>
      </div>
    </div>

    <!-- Reports -->
    <div x-data="{open: false}" class="mt-1">
      <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
        <span class="inline-flex items-center gap-2"><span class="material-symbols-outlined">analytics</span> Reports</span>
        <span class="material-symbols-outlined text-base" x-text="open ? 'expand_less' : 'expand_more'"></span>
      </button>
      <div x-show="open" x-collapse class="mt-1 ml-2 pl-4 border-l border-gray-100 space-y-1">
        <a href="{{ url('/super/reports/users') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">person_search</span>
          <span>User Reports</span>
        </a>
        <a href="{{ url('/super/reports/swaps') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">swap_vert_circle</span>
          <span>Swap Reports</span>
        </a>
      </div>
    </div>

    <!-- Payments / Subscription -->
    <div x-data="{open: false}" class="mt-1">
      <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
        <span class="inline-flex items-center gap-2"><span class="material-symbols-outlined">credit_card</span> Billing</span>
        <span class="material-symbols-outlined text-base" x-text="open ? 'expand_less' : 'expand_more'"></span>
      </button>
      <div x-show="open" x-collapse class="mt-1 ml-2 pl-4 border-l border-gray-100 space-y-1">
        <a href="{{ url('/super/payments') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">receipt_long</span>
          <span>Payments</span>
        </a>
        <a href="{{ url('/super/plans') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">workspace_premium</span>
          <span>Plans / Packages</span>
        </a>
      </div>
    </div>

    <!-- Settings -->
    <div x-data="{open: false}" class="mt-1">
      <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
        <span class="inline-flex items-center gap-2"><span class="material-symbols-outlined">settings</span> Settings</span>
        <span class="material-symbols-outlined text-base" x-text="open ? 'expand_less' : 'expand_more'"></span>
      </button>
      <div x-show="open" x-collapse class="mt-1 ml-2 pl-4 border-l border-gray-100 space-y-1">
        <a href="{{ url('/super/settings/general') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">tune</span>
          <span>General</span>
        </a>
        <a href="{{ url('/super/settings/channels') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">mail</span>
          <span>Email & SMS</span>
        </a>
        <a href="{{ url('/super/settings/access') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">shield_person</span>
          <span>Access Control</span>
        </a>
        <a href="{{ url('/super/settings/security') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">security</span>
          <span>Security</span>
        </a>
      </div>
    </div>

    <!-- Activity Logs -->
    <div x-data="{open: false}" class="mt-1">
      <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
        <span class="inline-flex items-center gap-2"><span class="material-symbols-outlined">history</span> Activity Logs</span>
        <span class="material-symbols-outlined text-base" x-text="open ? 'expand_less' : 'expand_more'"></span>
      </button>
      <div x-show="open" x-collapse class="mt-1 ml-2 pl-4 border-l border-gray-100 space-y-1">
        <a href="{{ url('/super/logs') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">article</span>
          <span>Activity Records</span>
        </a>
        <a href="{{ url('/super/audit') }}" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-gray-100">
          <span class="material-symbols-outlined text-base">assignment_turned_in</span>
          <span>Audit Trail</span>
        </a>
      </div>
    </div>


  </nav>
</aside>
