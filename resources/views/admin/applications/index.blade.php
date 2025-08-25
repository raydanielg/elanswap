@extends('layouts.admin')

@section('title', 'All Applications')

@section('content')
<div class="space-y-4">
    <!-- Title -->
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800">All Applications</h1>
        <div class="text-sm text-gray-500">Total: {{ $counts['total'] ?? 0 }}</div>
    </div>

    <hr class="border-0 border-t border-dashed border-gray-300">

    <!-- Filters -->
    <form method="get" class="bg-white border border-gray-200 sm:rounded-lg p-3 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
        <div class="flex items-center gap-2">
            <input type="text" name="q" value="{{ $q }}" placeholder="Search code or user name..." class="w-full sm:w-72 border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
            <button class="px-3 py-2 text-sm bg-gray-900 text-white rounded-md">Search</button>
        </div>
        <div class="flex items-center gap-2">
            <select name="status" class="border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                <option value="" {{ $status==='' ? 'selected' : '' }}>All Status</option>
                <option value="pending" {{ $status==='pending' ? 'selected' : '' }}>Pending ({{ $counts['pending'] ?? 0 }})</option>
                <option value="accepted" {{ $status==='accepted' ? 'selected' : '' }}>Accepted ({{ $counts['accepted'] ?? 0 }})</option>
                <option value="rejected" {{ $status==='rejected' ? 'selected' : '' }}>Rejected ({{ $counts['rejected'] ?? 0 }})</option>
            </select>
            <select name="exchanged" class="border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                <option value="" {{ $exchanged==='' ? 'selected' : '' }}>All Exchanges</option>
                <option value="yes" {{ $exchanged==='yes' ? 'selected' : '' }}>Exchanged</option>
                <option value="no" {{ $exchanged==='no' ? 'selected' : '' }}>Not Exchanged</option>
            </select>
            <button class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-md" type="submit">Apply</button>
        </div>
    </form>

    <!-- Table -->
    <div class="relative overflow-x-auto bg-white border border-gray-200 shadow-sm sm:rounded-lg w-full">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Code</th>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">From → To</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Completed</th>
                    <th class="px-4 py-3">Exchanged</th>
                    <th class="px-4 py-3">Submitted</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($apps as $app)
                <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-4 py-3 whitespace-nowrap">#{{ $app->id }}</td>
                    <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ $app->code ?? ('APP-'.$app->id) }}</td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900">{{ optional($app->user)->name ?? '—' }}</div>
                        <div class="text-xs text-gray-500">ID: {{ $app->user_id }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-gray-900">{{ optional($app->fromRegion)->name ?? '—' }} → {{ optional($app->toRegion)->name ?? '—' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        @php($st = $app->status)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                            {{ $st==='pending' ? 'bg-yellow-50 text-yellow-700 ring-1 ring-yellow-200' : '' }}
                            {{ $st==='accepted' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : '' }}
                            {{ $st==='rejected' ? 'bg-rose-50 text-rose-700 ring-1 ring-rose-200' : '' }}
                        ">{{ ucfirst($st) }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @php($completed = $app->status === 'accepted')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $completed ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-gray-50 text-gray-600 ring-1 ring-gray-200' }}">
                            {{ $completed ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @php($ex = !is_null($app->paired_application_id))
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $ex ? 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200' : 'bg-gray-50 text-gray-600 ring-1 ring-gray-200' }}">
                            {{ $ex ? 'Exchanged' : 'Not Exchanged' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">{{ $app->submitted_at?->format('Y-m-d H:i') ?? $app->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                        <div class="inline-flex items-center gap-3">
                            <button type="button" title="Preview" class="p-1.5 rounded hover:bg-primary-50 text-primary-700" data-peek-url="{{ route('admin.applications.peek', $app) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                            <form action="{{ route('admin.applications.destroy', $app) }}" method="post" onsubmit="return confirm('Delete this application?');">
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
                    <td colspan="9" class="px-6 py-10 text-center text-gray-500">No applications found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $apps->links() }}
    </div>
</div>

<!-- Modal -->
<div id="peek-modal" class="fixed inset-0 z-[70] hidden" aria-hidden="true">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-[1px]"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div id="peek-dialog" class="bg-white w-full max-w-md rounded-xl shadow-xl overflow-hidden ring-1 ring-black/5 opacity-0 scale-95 translate-y-2 transition duration-200 ease-out">
            <div class="flex items-center justify-between px-4 py-2 border-b bg-gray-50/60">
                <div class="text-sm font-medium text-gray-800">Application Preview</div>
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
        // animate in
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
        // animate out
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
