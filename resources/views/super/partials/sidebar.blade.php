<aside
  class="w-64 border-r border-gray-200 bg-white overflow-y-auto min-h-screen"
>
  <nav class="p-4 space-y-2 text-sm">
    <!-- Dashboard / Muhtasari -->
    <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100 text-gray-700 {{ request()->routeIs('superadmin.dashboard') ? 'bg-gray-100 font-medium' : '' }}">
      <span class="material-symbols-outlined">dashboard</span>
      <span>Dashboard</span>
    </a>
    <div class="ml-3 text-xs text-gray-500">Overview, key information, and analytics</div>

    <!-- User Management -->
    <div x-data="{open: false}" class="mt-2">
      <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded hover:bg-gray-100 text-gray-700">
        <span class="inline-flex items-center gap-2"><span class="material-symbols-outlined">group</span> User Management</span>
        <span class="material-symbols-outlined text-base" x-text="open ? 'expand_less' : 'expand_more'"></span>
      </button>
      <div x-show="open" x-collapse class="mt-1 pl-9 space-y-1">
        <a href="{{ url('/super/users') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Users List</a>
        <a href="{{ url('/super/users/create') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Add User</a>
        <a href="{{ url('/super/roles') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Roles & Permissions</a>
      </div>
    </div>

    <!-- Institutions / Organizations -->
    <div x-data="{open: false}" class="mt-2">
      <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded hover:bg-gray-100 text-gray-700">
        <span class="inline-flex items-center gap-2"><span class="material-symbols-outlined">domain</span> Institutions</span>
        <span class="material-symbols-outlined text-base" x-text="open ? 'expand_less' : 'expand_more'"></span>
      </button>
      <div x-show="open" x-collapse class="mt-1 pl-9 space-y-1">
        <a href="{{ url('/super/institutions') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Organizations List</a>
        <a href="{{ url('/super/institutions/create') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Register Organization</a>
        <a href="{{ url('/super/stations') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Workstations</a>
      </div>
    </div>

    <!-- Swap Requests -->
    <div x-data="{open: false}" class="mt-2">
      <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded hover:bg-gray-100 text-gray-700">
        <span class="inline-flex items-center gap-2"><span class="material-symbols-outlined">swap_horiz</span> Swap Requests</span>
        <span class="material-symbols-outlined text-base" x-text="open ? 'expand_less' : 'expand_more'"></span>
      </button>
      <div x-show="open" x-collapse class="mt-1 pl-9 space-y-1">
        <a href="{{ url('/super/swaps') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Requests List</a>
        <a href="{{ url('/super/swaps/pending') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Pending</a>
        <a href="{{ url('/super/swaps/approved') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Approved</a>
        <a href="{{ url('/super/swaps/rejected') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Rejected</a>
        <a href="{{ url('/super/swaps/stats') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Statistics</a>
      </div>
    </div>

    <!-- Reports -->
    <div x-data="{open: false}" class="mt-2">
      <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded hover:bg-gray-100 text-gray-700">
        <span class="inline-flex items-center gap-2"><span class="material-symbols-outlined">analytics</span> Reports</span>
        <span class="material-symbols-outlined text-base" x-text="open ? 'expand_less' : 'expand_more'"></span>
      </button>
      <div x-show="open" x-collapse class="mt-1 pl-9 space-y-1">
        <a href="{{ url('/super/reports/users') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">User Reports</a>
        <a href="{{ url('/super/reports/swaps') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Swap Reports</a>
        <a href="{{ url('/super/reports/export') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Export PDF/Excel</a>
      </div>
    </div>

    <!-- Payments / Subscription -->
    <div x-data="{open: false}" class="mt-2">
      <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded hover:bg-gray-100 text-gray-700">
        <span class="inline-flex items-center gap-2"><span class="material-symbols-outlined">credit_card</span> Payments / Subscription</span>
        <span class="material-symbols-outlined text-base" x-text="open ? 'expand_less' : 'expand_more'"></span>
      </button>
      <div x-show="open" x-collapse class="mt-1 pl-9 space-y-1">
        <a href="{{ url('/super/payments') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Payments List</a>
        <a href="{{ url('/super/payments/verify') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Verify Payment</a>
        <a href="{{ url('/super/plans') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Plans / Packages</a>
      </div>
    </div>

    <!-- Settings -->
    <div x-data="{open: false}" class="mt-2">
      <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded hover:bg-gray-100 text-gray-700">
        <span class="inline-flex items-center gap-2"><span class="material-symbols-outlined">settings</span> Settings</span>
        <span class="material-symbols-outlined text-base" x-text="open ? 'expand_less' : 'expand_more'"></span>
      </button>
      <div x-show="open" x-collapse class="mt-1 pl-9 space-y-1">
        <a href="{{ url('/super/settings/general') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">General (Name, Logo, Theme)</a>
        <a href="{{ url('/super/settings/channels') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Email & SMS (SMTP, Gateway)</a>
        <a href="{{ url('/super/settings/access') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Access Control (Roles & Permissions)</a>
        <a href="{{ url('/super/settings/security') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Security (2FA, Backup, Audit)</a>
      </div>
    </div>

    <!-- Notifications -->
    <div x-data="{open: false}" class="mt-2">
      <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded hover:bg-gray-100 text-gray-700">
        <span class="inline-flex items-center gap-2"><span class="material-symbols-outlined">notifications</span> Notifications</span>
        <span class="material-symbols-outlined text-base" x-text="open ? 'expand_less' : 'expand_more'"></span>
      </button>
      <div x-show="open" x-collapse class="mt-1 pl-9 space-y-1">
        <a href="{{ url('/super/notifications/send') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Send Notification</a>
        <a href="{{ url('/super/notifications/broadcast') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Broadcast Messages</a>
      </div>
    </div>

    <!-- Activity Logs -->
    <div x-data="{open: false}" class="mt-2">
      <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded hover:bg-gray-100 text-gray-700">
        <span class="inline-flex items-center gap-2"><span class="material-symbols-outlined">history</span> Activity Logs</span>
        <span class="material-symbols-outlined text-base" x-text="open ? 'expand_less' : 'expand_more'"></span>
      </button>
      <div x-show="open" x-collapse class="mt-1 pl-9 space-y-1">
        <a href="{{ url('/super/logs') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Activity Records</a>
        <a href="{{ url('/super/audit') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Audit Trail</a>
      </div>
    </div>

    <!-- Support & Help Center -->
    <div x-data="{open: false}" class="mt-2">
      <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded hover:bg-gray-100 text-gray-700">
        <span class="inline-flex items-center gap-2"><span class="material-symbols-outlined">support_agent</span> Support & Help</span>
        <span class="material-symbols-outlined text-base" x-text="open ? 'expand_less' : 'expand_more'"></span>
      </button>
      <div x-show="open" x-collapse class="mt-1 pl-9 space-y-1">
        <a href="{{ url('/super/support/tickets') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">Support Tickets</a>
        <a href="{{ url('/super/support/faq') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100">FAQ / Documentation</a>
      </div>
    </div>

  </nav>
</aside>
