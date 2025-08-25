@extends('layouts.admin')

@section('content')
<div class="space-y-4">
    <div>
        <h1 class="text-xl font-semibold">Announcements</h1>
        <div class="border-b border-dashed border-gray-300 mt-2"></div>
    </div>

    <div class="flex items-center justify-between gap-3 mb-4">
        <form action="{{ route('admin.features.index') }}" method="GET" class="flex items-center gap-2" onsubmit="return false;">
            <input id="feature-search" type="text" name="q" value="{{ request('q') }}" placeholder="Search announcements..." class="h-9 w-64 rounded border border-gray-300 px-3 text-sm focus:outline-none focus:ring-1 focus:ring-primary-500" />
            @if(request('q'))
                <a href="{{ route('admin.features.index') }}" class="h-9 px-3 rounded border text-sm hover:bg-gray-50">Clear</a>
            @endif
        </form>
        <a href="{{ route('admin.features.create') }}" class="h-9 inline-flex items-center px-3 rounded bg-primary-600 text-white text-sm hover:bg-primary-700">Add +</a>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 bg-emerald-50 text-emerald-700 rounded">{{ session('status') }}</div>
    @endif

    <div id="features-list" class="mt-6">
        @include('admin.features._list')
    </div>
    <script>
    (function() {
        const input = document.getElementById('feature-search');
        const container = document.getElementById('features-list');
        let t;
        function bindCheckboxes(){
            const master = container.querySelector('#checkbox-all');
            const rows = () => Array.from(container.querySelectorAll('.row-check'));
            if (master) {
                master.addEventListener('change', () => {
                    rows().forEach(cb => { cb.checked = master.checked; });
                });
            }
        }
        function fetchList(url){
            const u = new URL(url || '{{ route('admin.features.index') }}', window.location.origin);
            const q = input.value.trim();
            if (q) u.searchParams.set('q', q); else u.searchParams.delete('q');
            fetch(u.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.text())
                .then(html => { container.innerHTML = html; bindCheckboxes(); })
                .catch(() => {});
        }
        if (input) {
            input.addEventListener('input', () => {
                clearTimeout(t);
                t = setTimeout(() => fetchList(), 250);
            });
        }
        bindCheckboxes();
        // AJAX pagination inside container
        container.addEventListener('click', (e) => {
            const a = e.target.closest('a');
            if (!a) return;
            if (a.closest('nav') || a.closest('.pagination')) {
                e.preventDefault();
                fetchList(a.href);
            }
        });
    })();
    </script>
</div>
@endsection
