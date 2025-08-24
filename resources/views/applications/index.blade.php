@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Title -->
    <div class="mb-2">
        <h1 class="text-2xl font-bold text-gray-900">My Application</h1>
    </div>
    <!-- Divider -->
    <div class="border-t border-dashed border-gray-300 mb-6"></div>

    <!-- Actions: Search (left) and Add (right) -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 md:gap-4 mb-5">
        <div class="w-full md:max-w-xl">
            <label for="app-search" class="sr-only">Search</label>
            <input id="app-search" type="text" placeholder="Search applications..." class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" />
        </div>
        <div class="md:ml-4 w-full md:w-auto">
            <a href="{{ route('applications.create') }}" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">
                + Add
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-md border border-gray-200 shadow-sm">
        <div class="overflow-x-auto -mx-4 sm:mx-0">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Code</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Applicant</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">From Region</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">To Region</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody id="apps-tbody" class="divide-y divide-gray-100">
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-4 py-3 bg-gray-50 border-t">
            <div class="text-sm text-gray-600" id="apps-summary">&nbsp;</div>
            <div class="flex items-center gap-2">
                <button id="apps-prev" class="px-3 py-1 border rounded disabled:opacity-50">Prev</button>
                <button id="apps-next" class="px-3 py-1 border rounded disabled:opacity-50">Next</button>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    const tbody = document.getElementById('apps-tbody');
    const summary = document.getElementById('apps-summary');
    const prevBtn = document.getElementById('apps-prev');
    const nextBtn = document.getElementById('apps-next');
    const searchInput = document.getElementById('app-search');

    let page = 1;
    let lastPage = 1;
    let q = '';
    let debounceTimer;

    function attachCopyHandlers(){
        const buttons = tbody.querySelectorAll('button.copy-code');
        buttons.forEach(btn => {
            btn.addEventListener('click', async () => {
                const code = btn.getAttribute('data-code') || '';
                try{
                    await navigator.clipboard.writeText(code);
                    const original = btn.innerHTML;
                    btn.innerHTML = `<span class="text-green-600">Copied</span>`;
                    setTimeout(() => { btn.innerHTML = original; }, 1200);
                }catch(e){
                    console.error(e);
                }
            });
        });
    }

    function renderRows(items){
        if(!items || items.length === 0){
            tbody.innerHTML = '<tr><td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">No applications found</td></tr>';
            return;
        }
        tbody.innerHTML = items.map(i => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 text-sm text-gray-700">${i.id}</td>
                <td class="px-4 py-2 text-sm text-gray-700">
                    ${i.code ? `<button type="button" class="copy-code inline-flex items-center gap-1 px-2 py-1 rounded hover:bg-gray-100" data-code="${escapeHtml(i.code)}" title="Click to copy">
                        <span class="font-mono">${escapeHtml(i.code)}</span>
                    </button>` : ''}
                </td>
                <td class="px-4 py-2 text-sm text-gray-700">${escapeHtml(i.user || '')}</td>
                <td class="px-4 py-2 text-sm text-gray-700">${escapeHtml(i.from || '')}</td>
                <td class="px-4 py-2 text-sm text-gray-700">${escapeHtml(i.to || '')}</td>
                <td class="px-4 py-2 text-sm">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">${escapeHtml(i.status_label || '')}</span>
                </td>
                <td class="px-4 py-2 text-sm text-gray-700">${escapeHtml(i.date || '')}</td>
                <td class="px-4 py-2 text-sm">
                    <div class="flex items-center justify-end gap-2">
                        <a href="${i.show_url}" class="inline-flex items-center px-2 py-1 border rounded hover:bg-gray-50" title="View">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-700"><path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <button type="button" class="inline-flex items-center px-2 py-1 border rounded hover:bg-gray-50" title="Notify" onclick="notify(${i.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-amber-600"><path d="M12 2a6 6 0 0 0-6 6v3.586l-1.707 1.707A1 1 0 0 0 5 15h14a1 1 0 0 0 .707-1.707L18 11.586V8a6 6 0 0 0-6-6zm0 20a3 3 0 0 0 3-3H9a3 3 0 0 0 3 3z"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
        attachCopyHandlers();
    }

    function escapeHtml(s){
        return String(s).replace(/[&<>"]+/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
    }

    window.notify = function(id){
        // Placeholder action; integrate with notifications later
        alert('Notification will be sent for application #' + id);
    }

    async function fetchData(){
        tbody.innerHTML = '<tr><td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">Loading...</td></tr>';
        try{
            const params = new URLSearchParams({ q, page, per_page: 10 });
            const res = await fetch(`{{ route('applications.search') }}?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                credentials: 'same-origin',
            });
            if (!res.ok) {
                const text = await res.text();
                throw new Error(`Request failed (${res.status}) ${res.statusText}: ${text?.slice(0, 200)}`);
            }
            const json = await res.json();
            renderRows(json.data || []);
            const meta = json.meta || {};
            page = meta.current_page || 1;
            lastPage = meta.last_page || 1;
            const total = meta.total || 0;
            summary.textContent = `Page ${page} of ${lastPage} — ${total} total`;
            prevBtn.disabled = page <= 1;
            nextBtn.disabled = page >= lastPage;
        }catch(e){
            console.error(e);
            tbody.innerHTML = '<tr><td colspan="8" class="px-4 py-6 text-center text-sm text-red-600">Failed to load</td></tr>';
            summary.textContent = '';
            prevBtn.disabled = true;
            nextBtn.disabled = true;
        }
    }

    prevBtn.addEventListener('click', () => { if(page>1){ page--; fetchData(); } });
    nextBtn.addEventListener('click', () => { if(page<lastPage){ page++; fetchData(); } });

    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            q = searchInput.value.trim();
            page = 1;
            fetchData();
        }, 300);
    });

    // No modal; view via show page

    fetchData();
})();
</script>
@endsection
