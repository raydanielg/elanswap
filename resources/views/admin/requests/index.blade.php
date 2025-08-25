@extends('layouts.admin')

@section('title', 'All Exchange Requests')

@section('content')
<div class="space-y-4">
    <!-- Title -->
    <h1 class="text-xl font-semibold text-gray-800">All Exchange Requests</h1>
    <!-- dashed divider -->
    <hr class="border-0 border-t border-dashed border-gray-300">

    <!-- Status Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2">
        @php($active = $status)
        <button type="button" data-status="" class="tab-btn px-3 py-1.5 rounded-md border text-sm {{ $active==='' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">All <span class="ml-1 text-xs text-gray-500">({{ ($counts['pending'] + $counts['accepted'] + $counts['rejected']) ?? 0 }})</span></button>
        <button type="button" data-status="pending" class="tab-btn px-3 py-1.5 rounded-md border text-sm {{ $active==='pending' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">Pending <span class="ml-1 text-xs text-gray-500">({{ $counts['pending'] ?? 0 }})</span></button>
        <button type="button" data-status="accepted" class="tab-btn px-3 py-1.5 rounded-md border text-sm {{ $active==='accepted' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">Approved <span class="ml-1 text-xs text-gray-500">({{ $counts['accepted'] ?? 0 }})</span></button>
        <button type="button" data-status="rejected" class="tab-btn px-3 py-1.5 rounded-md border text-sm {{ $active==='rejected' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">Rejected <span class="ml-1 text-xs text-gray-500">({{ $counts['rejected'] ?? 0 }})</span></button>
    </div>

    <!-- Controls: left search (AJAX), right filter -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center gap-2">
            <div class="relative">
                <input id="requests-search" type="text" value="{{ $q }}" placeholder="Search name, message, or code..." class="w-full sm:w-72 border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
            </div>
        </div>
        <div class="flex items-center gap-2">
            <select id="requests-status" class="border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                <option value="" {{ $status==='' ? 'selected' : '' }}>All</option>
                <option value="pending" {{ $status==='pending' ? 'selected' : '' }}>Pending</option>
                <option value="accepted" {{ $status==='accepted' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $status==='rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
    </div>

    <!-- Results -->
    <div id="requests-results">
        @include('admin.requests._table', ['requests' => $requests])
    </div>

    <!-- Modal -->
    <div id="peek-modal" class="fixed inset-0 z-[70] hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="absolute inset-0 flex items-start sm:items-center justify-center p-4">
            <div class="bg-white w-full max-w-2xl rounded-lg shadow-lg overflow-hidden">
                <div class="flex items-center justify-between px-4 py-2 border-b">
                    <div class="text-sm font-medium text-gray-700">Application Preview</div>
                    <button type="button" id="peek-close" class="p-1 rounded hover:bg-gray-100">✕</button>
                </div>
                <div id="peek-body" class="max-h-[70vh] overflow-auto"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('requests-search');
    const statusSelect = document.getElementById('requests-status');
    const results = document.getElementById('requests-results');

    let timer = null;
    const debounce = (fn, wait=300) => {
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => fn.apply(null, args), wait);
        };
    };

    const load = async (url = null) => {
        const params = new URLSearchParams();
        const q = searchInput.value.trim();
        const status = statusSelect.value;
        if (!url) {
            if (q) params.set('q', q);
            if (status) params.set('status', status);
            url = `{{ route('admin.requests.index') }}${params.toString() ? ('?' + params.toString()) : ''}`;
        } else {
            const u = new URL(url, window.location.origin);
            if (q) u.searchParams.set('q', q); else u.searchParams.delete('q');
            if (status) u.searchParams.set('status', status); else u.searchParams.delete('status');
            url = u.toString();
        }
        results.classList.add('opacity-50');
        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const html = await res.text();
            results.innerHTML = html;
            results.classList.remove('opacity-50');
            bindPagination();
            bindPeekButtons();
            history.replaceState({}, '', url);
        } catch (e) {
            results.classList.remove('opacity-50');
        }
    };

    const bindPagination = () => {
        results.querySelectorAll('a').forEach(a => {
            if (a.closest('.pagination') || a.href.includes('page=')) {
                a.addEventListener('click', (ev) => {
                    ev.preventDefault();
                    load(a.href);
                });
            }
        });
    };

    searchInput.addEventListener('input', debounce(() => load(), 300));
    statusSelect.addEventListener('change', () => load());
    // Tabs -> change status select then load
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const s = btn.getAttribute('data-status') || '';
            statusSelect.value = s;
            // Update active styles (simple)
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('bg-gray-900','text-white','border-gray-900'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.add('bg-white','text-gray-700','border-gray-300'));
            btn.classList.remove('bg-white','text-gray-700','border-gray-300');
            btn.classList.add('bg-gray-900','text-white','border-gray-900');
            load();
        });
    });
    bindPagination();
    
    // Modal helpers
    const modal = document.getElementById('peek-modal');
    const modalBody = document.getElementById('peek-body');
    const modalClose = document.getElementById('peek-close');
    const openModal = async (url) => {
        if (!url) return;
        modal.classList.remove('hidden');
        modalBody.innerHTML = '<div class="p-4 text-sm text-gray-500">Loading...</div>';
        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const html = await res.text();
            modalBody.innerHTML = html;
        } catch (err) {
            modalBody.innerHTML = '<div class="p-4 text-sm text-red-600">Failed to load preview.</div>';
        }
    };
    const closeModal = () => { modal.classList.add('hidden'); modalBody.innerHTML = ''; };
    modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
    modalClose.addEventListener('click', closeModal);

    const bindPeekButtons = () => {
        results.querySelectorAll('[data-peek-url]').forEach(btn => {
            btn.addEventListener('click', () => openModal(btn.getAttribute('data-peek-url')));
        });
    };
    bindPeekButtons();
});
</script>
@endpush
@endsection
