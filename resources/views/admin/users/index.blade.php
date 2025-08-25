@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6">
    <h1 class="text-xl font-semibold text-gray-800">All Users</h1>

    <div class="mt-4 grid gap-3 sm:grid-cols-4">
        <form method="get" class="sm:col-span-3">
            <div class="flex gap-2">
                <input type="text" name="q" value="{{ $q }}" placeholder="Search name, email, phone" class="w-full rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500" />
                <select name="role" class="rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Roles</option>
                    <option value="user" @selected($role==='user')>User</option>
                    <option value="admin" @selected($role==='admin')>Admin</option>
                    <option value="superadmin" @selected($role==='superadmin')>Superadmin</option>
                </select>
                <select name="banned" class="rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All</option>
                    <option value="yes" @selected($banned==='yes')>Banned</option>
                    <option value="no" @selected($banned==='no')>Not Banned</option>
                </select>
                <button class="px-3 py-2 rounded-md bg-primary-600 text-white">Filter</button>
            </div>
        </form>
        <div class="sm:col-span-1 flex items-center justify-end gap-2 text-xs text-gray-600">
            <span>Total: {{ $counts['total'] ?? '' }}</span>
            <span>•</span>
            <span>Banned: {{ $counts['banned'] ?? '' }}</span>
            <span>•</span>
            <span>Admins: {{ $counts['admins'] ?? '' }}</span>
        </div>
    </div>

    <div class="mt-4 overflow-x-auto bg-white rounded-lg shadow ring-1 ring-black/5">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Phone</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Role</th>
                    <th class="px-4 py-2 text-left">Banned</th>
                    <th class="px-4 py-2 text-left">Region</th>
                    <th class="px-4 py-2 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse($users as $u)
                <tr class="hover:bg-gray-50/60">
                    <td class="px-4 py-3">{{ $u->id }}</td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-800">{{ $u->name }}</div>
                        <div class="text-xs text-gray-500">Joined {{ $u->created_at->format('Y-m-d') }}</div>
                    </td>
                    <td class="px-4 py-3">{{ $u->phone }}</td>
                    <td class="px-4 py-3">{{ $u->email }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ in_array($u->role, ['admin','superadmin']) ? 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200' : 'bg-gray-50 text-gray-600 ring-1 ring-gray-200' }}">{{ ucfirst($u->role ?? 'user') }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @if($u->is_banned)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-rose-50 text-rose-700 ring-1 ring-rose-200">Yes</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 ring-1 ring-green-200">No</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-600">{{ optional($u->region)->name }}</td>
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                        <div class="inline-flex items-center gap-3">
                            <button type="button" title="Preview" class="p-1.5 rounded hover:bg-primary-50 text-primary-700" data-peek-url="{{ route('admin.users.peek', $u) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                            @if(!$u->is_banned)
                                <form action="{{ route('admin.users.ban', $u) }}" method="post" onsubmit="return confirm('Ban this user?');">
                                    @csrf
                                    <input type="hidden" name="reason" value="Admin action" />
                                    <button title="Ban" class="p-1.5 rounded hover:bg-rose-50 text-rose-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.536-11.536a5 5 0 00-7.072 7.072l7.072-7.072zM6.464 13.536a5 5 0 007.072-7.072l-7.072 7.072z" clip-rule="evenodd"/></svg>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.users.unban', $u) }}" method="post" onsubmit="return confirm('Unban this user?');">
                                    @csrf
                                    <button title="Unban" class="p-1.5 rounded hover:bg-green-50 text-green-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 12a5 5 0 1110 0 5 5 0 01-10 0z"/></svg>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.users.reset', $u) }}" method="post" onsubmit="return confirm('Send password reset email to this user?');">
                                @csrf
                                <button title="Send reset" class="p-1.5 rounded hover:bg-indigo-50 text-indigo-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </button>
                            </form>
                            <form action="{{ route('admin.users.destroy', $u) }}" method="post" onsubmit="return confirm('Delete this user? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button title="Delete" class="p-1.5 rounded hover:bg-rose-50 text-rose-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-3h4m-6 3h8"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-10 text-center text-sm text-gray-500">No users found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $users->links() }}
    </div>
</div>

<!-- Modal -->
<div id="peek-modal" class="fixed inset-0 z-[70] hidden" aria-hidden="true">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-[1px]"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div id="peek-dialog" class="bg-white w-full max-w-md rounded-xl shadow-xl overflow-hidden ring-1 ring-black/5 opacity-0 scale-95 translate-y-2 transition duration-200 ease-out">
            <div class="flex items-center justify-between px-4 py-2 border-b bg-gray-50/60">
                <div class="text-sm font-medium text-gray-800">User Preview</div>
                <button type="button" id="peek-close" class="p-1.5 rounded hover:bg-gray-200" title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
            <div id="peek-body" class="max-h-[70vh] overflow-auto"></div>
        </div>
    </div>
    
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('peek-modal');
        const modalBody = document.getElementById('peek-body');
        const dialog = document.getElementById('peek-dialog');
        const modalClose = document.getElementById('peek-close');
        const openModal = async (url) => {
            if (!url) return;
            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                dialog.classList.remove('opacity-0','scale-95','translate-y-2');
                dialog.classList.add('opacity-100','scale-100','translate-y-0');
            });
            modalBody.innerHTML = '<div class="p-4 text-sm text-gray-500">Loading...</div>';
            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await res.text();
                modalBody.innerHTML = html;
            } catch (err) {
                modalBody.innerHTML = '<div class="p-4 text-sm text-red-600">Failed to load preview.</div>';
            }
        };
        const closeModal = () => {
            dialog.classList.remove('opacity-100','scale-100','translate-y-0');
            dialog.classList.add('opacity-0','scale-95','translate-y-2');
            setTimeout(() => { modal.classList.add('hidden'); modalBody.innerHTML = ''; }, 180);
        };
        modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
        modalClose.addEventListener('click', closeModal);
        document.querySelectorAll('[data-peek-url]').forEach(btn => {
            btn.addEventListener('click', () => openModal(btn.getAttribute('data-peek-url')));
        });
    });
</script>
@endpush
@endsection
