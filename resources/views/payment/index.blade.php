<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Malipo ya Huduma
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="relative p-6 bg-white shadow sm:rounded-lg">
                <div class="flex flex-col gap-4">
                    <div>
                        <div class="mb-3 flex items-center gap-3">
                            <img src="{{ asset('download (1).png') }}" alt="logo-1" class="w-10 h-10 rounded-full object-cover ring-1 ring-gray-200">
                            <img src="{{ asset('download (2).png') }}" alt="logo-2" class="w-10 h-10 rounded-full object-cover ring-1 ring-gray-200">
                            <img src="{{ asset('download.jpg') }}" alt="logo-3" class="w-10 h-10 rounded-full object-cover ring-1 ring-gray-200">
                            <img src="{{ asset('download.png') }}" alt="logo-4" class="w-10 h-10 rounded-full object-cover ring-1 ring-gray-200">
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Muhtasari</h3>
                        <p class="text-gray-700">Kiasi: <span class="font-medium">TZS {{ number_format($amount) }}</span></p>
                        <p class="text-gray-700 mt-1">Order ID: <span id="orderIdVal">{{ ($latest && is_array($latest->meta ?? null) && isset(($latest->meta)['order_id'])) ? ($latest->meta)['order_id'] : '-' }}</span></p>

                        <div class="mt-4 text-sm" id="summaryCard" data-has-latest="{{ $latest ? '1' : '0' }}" data-paid="{{ $latest && $latest->paid_at ? '1' : '0' }}">
                            <p class="text-gray-600 mb-2">Hali ya malipo yako ya karibuni:</p>
                            <div class="flex items-center gap-3">
                                <span id="statusBadge" class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    @if($latest && $latest->paid_at)
                                        <svg class="animate-pulse h-3 w-3 text-green-500 mr-1" viewBox="0 0 8 8" fill="currentColor"><circle cx="4" cy="4" r="4"/></svg>
                                        PAID
                                    @elseif($latest)
                                        <svg class="animate-spin h-3 w-3 text-amber-500 mr-1" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                        PENDING
                                    @else
                                        HAKUNA MALIPO
                                    @endif
                                </span>
                                <span id="timeInfo" class="text-gray-500"></span>
                            </div>
                            @if($latest && $latest->paid_at)
                                <div class="mt-1 text-gray-700">Imethibitishwa: <span id="paidTime">{{ $latest->paid_at->format('Y-m-d H:i') }}</span></div>
                            @endif
                            @if(false) @endif
                            <div id="statusAlert" class="mt-3 hidden"></div>
                        </div>
                    </div>
                </div>
            </div>

            @if(!auth()->user()->hasPaid())
                <div class="p-6 bg-white shadow sm:rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Lipa Sasa</h3>
                            <p class="text-sm text-gray-600">Bonyeza kitufe hapa chini kuingiza namba ya simu na kutuma ombi la malipo.</p>
                        </div>
                        <button id="openPayModal" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">Lipa Sasa</button>
                    </div>
                    <div id="pushStatus" class="mt-3 text-sm text-gray-700 hidden"></div>
                </div>
            @else
                <div class="p-6 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-800">Asante! Malipo yako yamekamilika. Unaweza kuendelea kutumia huduma zote.</p>
                    <div class="mt-2 text-sm">
                        <a href="{{ route('dashboard') }}" class="text-primary-600 hover:underline">Nenda kwenye Dashboard</a>
                    </div>
                </div>
            @endif

            <div class="p-6 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-semibold mb-3">Oda Zako za Hivi Karibuni</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium text-gray-700">Tarehe</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-700">Order ID</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-700">Kiasi</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-700">Njia</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-700">Hali</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-700">Reference</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse(($orders ?? []) as $o)
                                @php($meta = (array) ($o->meta ?? []))
                                <tr>
                                    <td class="px-3 py-2 text-gray-700 whitespace-nowrap">{{ $o->created_at?->format('Y-m-d H:i') }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $meta['order_id'] ?? '-' }}</td>
                                    <td class="px-3 py-2 text-gray-700">TZS {{ number_format((int) $o->amount) }}</td>
                                    <td class="px-3 py-2 text-gray-700 uppercase">{{ $o->method ?? '-' }}</td>
                                    <td class="px-3 py-2">
                                        @if($o->paid_at || ($o->status === 'paid'))
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-green-100 text-green-800">PAID</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-amber-50 text-amber-700">PENDING</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-gray-700">{{ $o->provider_reference ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-4 text-gray-500">Bado hujafanya malipo.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<!-- Modal: Ingiza Namba ya Simu -->
<div id="payModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
        <div class="flex items-start justify-between mb-3">
            <h3 class="text-lg font-semibold">Ingiza Namba ya Simu</h3>
            <button id="closePayModal" class="text-gray-500 hover:text-gray-700" aria-label="Funga">&times;</button>
        </div>
        <p class="text-sm text-gray-600 mb-4">Weka namba utakayopokea ombi la malipo (mf. 07XXXXXXXX au 2557XXXXXXXX). Mfumo utatuma ombi la USSD/Push.</p>
        <form id="pushForm" method="POST" action="{{ route('payment.push') }}" class="space-y-4">
            @csrf
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Namba ya Simu</label>
                <input id="phone" name="phone" type="tel" inputmode="numeric" pattern="[0-9+]{9,15}" maxlength="15" placeholder="07XXXXXXXX au 2557XXXXXXXX" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required>
                <p id="phoneHint" class="mt-1 text-xs text-gray-500">Ruhusu tarakimu na + pekee. Mfano: 0712XXXXXX au 2557XXXXXXX.</p>
            </div>
            @error('phone')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            <div class="flex items-center justify-end gap-3">
                <button type="button" id="cancelPayModal" class="px-4 py-2 rounded-md border text-gray-700">Ghairi</button>
                <button id="pushBtn" type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 transition">
                    <svg id="btnSpinner" class="hidden animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                    <span id="btnText">Tuma Ombi</span>
                </button>
            </div>
        </form>
    </div>
    <div class="absolute inset-0 -z-10" aria-hidden="true"></div>
  </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('pushForm');
    const btn = document.getElementById('pushBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const statusBox = document.getElementById('pushStatus');
    const pushUrl = form.getAttribute('action');
    const statusUrlBase = '{{ route('payment.status') }}';
    const dashboardUrl = '{{ route('dashboard') }}';
    const orderIdSpan = document.getElementById('orderIdVal');
    let currentOrderId = (orderIdSpan && orderIdSpan.textContent && orderIdSpan.textContent !== '-') ? orderIdSpan.textContent.trim() : '';

    const show = (el) => { el.classList.remove('hidden'); };
    const hide = (el) => { el.classList.add('hidden'); };

    // Modal controls
    const modal = document.getElementById('payModal');
    const modalCard = modal ? modal.querySelector('.bg-white') : null;
    const openBtn = document.getElementById('openPayModal');
    const closeBtn = document.getElementById('closePayModal');
    const cancelBtn = document.getElementById('cancelPayModal');
    const phoneInput = document.getElementById('phone');
    const showModal = () => {
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        setTimeout(() => { phoneInput?.focus(); }, 50);
    };
    const hideModal = () => {
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    };
    if (openBtn) openBtn.addEventListener('click', showModal);
    if (closeBtn) closeBtn.addEventListener('click', hideModal);
    if (cancelBtn) cancelBtn.addEventListener('click', hideModal);
    // overlay click closes
    if (modal) {
        modal.addEventListener('click', (e) => { if (e.target === modal) hideModal(); });
        // prevent click-through inside card
        modalCard?.addEventListener('click', (e) => e.stopPropagation());
    }
    // ESC key closes
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !modal.classList.contains('hidden')) hideModal(); });

    // Phone input: allow digits and plus, strip others
    if (phoneInput) {
        phoneInput.addEventListener('input', () => {
            const cleaned = phoneInput.value.replace(/[^0-9+]/g, '');
            if (cleaned !== phoneInput.value) phoneInput.value = cleaned;
        });
    }

    if (!form) return;
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (btn) {
            btn.disabled = true;
            if (btnText) btnText.textContent = 'Inatuma...';
            if (btnSpinner) btnSpinner.classList.remove('hidden');
        }
        if (statusBox) { statusBox.textContent = 'Inatuma ombi la malipo...'; show(statusBox); }

        try {
            const formData = new FormData(form);
            const res = await fetch(pushUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: formData
            });
            const data = await res.json();
            if (!data.ok) {
                // Surface backend debug for local env if provided
                let msg = (data && data.message) ? data.message : 'Imeshindikana kutuma ombi';
                if (data.debug) {
                    const http = data.debug.push_http_status;
                    const body = data.debug.push_body;
                    const providerMsg = (body && (body.message || body.status_message)) ? (body.message || body.status_message) : '';
                    msg += ` (HTTP ${http}${providerMsg ? `: ${providerMsg}` : ''})`;
                }
                throw new Error(msg);
            }

            if (orderIdSpan && data.order_id) {
                orderIdSpan.textContent = data.order_id;
                currentOrderId = data.order_id;
            }
            hideModal();
            if (statusBox) {
                statusBox.textContent = 'Ombi limetumwa. Inasubiri uthibitisho kwenye simu yako...';
            }

            // Poll status until paid
            const start = Date.now();
            const timeoutMs = 120000; // 2 minutes
            const poll = async () => {
                if (Date.now() - start > timeoutMs) {
                    if (statusBox) statusBox.textContent = 'Muda umeisha. Tafadhali jaribu tena au hakikisha umethibitisha kwenye simu.';
                    if (btn) { btn.disabled = false; btn.textContent = 'Tuma Ombi la Malipo'; }
                    return;
                }
                try {
                    const q = currentOrderId ? ('?order_id=' + encodeURIComponent(currentOrderId)) : '';
                    const r = await fetch(statusUrlBase + q, { headers: { 'Accept': 'application/json' } });
                    const s = await r.json();
                    if (s && s.ok && s.paid) {
                        statusBox.textContent = 'Malipo yamekamilika! Inafungua ukurasa...';
                        window.location.href = dashboardUrl;
                        return;
                    }
                } catch (err) { /* ignore single poll errors */ }
                setTimeout(poll, 2000);
            };
            setTimeout(poll, 2000);
        } catch (err) {
            if (statusBox) { statusBox.textContent = 'Kosa: ' + (err?.message || 'Imeshindikana kutuma ombi'); show(statusBox); }
            if (btn) {
                btn.disabled = false;
                if (btnText) btnText.textContent = 'Tuma Ombi';
                if (btnSpinner) btnSpinner.classList.add('hidden');
            }
        }
    });
});

// Summary card live status
document.addEventListener('DOMContentLoaded', function () {
    const statusUrl = '{{ route('payment.status') }}';
    const summary = document.getElementById('summaryCard');
    if (!summary) return;
    const hasLatest = summary.getAttribute('data-has-latest') === '1';
    const isPaid = summary.getAttribute('data-paid') === '1';
    const badge = document.getElementById('statusBadge');
    const methodBadge = document.getElementById('methodBadge');
    const timeInfo = document.getElementById('timeInfo');
    const paidTime = document.getElementById('paidTime');
    const alertBox = document.getElementById('statusAlert');

    const setBadge = (html, classes) => { if (badge) { badge.innerHTML = html; badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium ' + classes; } };
    const setAlert = (text, classes) => {
        if (!alertBox) return;
        alertBox.className = 'mt-3 rounded-md p-3 text-sm ' + classes;
        alertBox.textContent = text;
        alertBox.classList.remove('hidden');
    };

    if (!hasLatest || isPaid) return;

    const start = Date.now();
    const timeoutMs = 120000; // 2 minutes max wait

    const spinner = '<svg class="animate-spin h-3 w-3 text-amber-500 mr-1" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg> PENDING';
    const paidDot = '<svg class="animate-pulse h-3 w-3 text-green-500 mr-1" viewBox="0 0 8 8" fill="currentColor"><circle cx="4" cy="4" r="4"/></svg> PAID';
    const failedIcon = '<svg class="h-3 w-3 text-red-500 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5h2v2H9v-2zm0-8h2v6H9V5z" clip-rule="evenodd"/></svg> FAILED';

    const poll = async () => {
        try {
            const r = await fetch(statusUrl, { headers: { 'Accept': 'application/json' } });
            const s = await r.json();
            if (s && s.ok) {
                if (methodBadge && s.method) methodBadge.textContent = 'Njia: ' + (s.method || '-').toUpperCase();
                if (s.paid) {
                    setBadge(paidDot, 'bg-green-100 text-green-800');
                    if (timeInfo) timeInfo.textContent = '';
                    if (paidTime && s.paid_at) paidTime.textContent = new Date(s.paid_at).toLocaleString();
                    if (alertBox) alertBox.classList.add('hidden');
                    return; // stop polling
                } else {
                    setBadge(spinner, 'bg-amber-50 text-amber-700');
                    if (timeInfo) timeInfo.textContent = '';
                    if (Date.now() - start > timeoutMs) {
                        setBadge(failedIcon, 'bg-red-100 text-red-800');
                        setAlert('Malipo hayajakamilika kwa muda uliowekwa. Tafadhali jaribu tena au hakikisha umethibitisha kwenye simu.', 'bg-red-50 border border-red-200 text-red-800');
                        return; // stop polling
                    }
                }
            }
        } catch (e) { /* ignore single poll error */ }
        setTimeout(poll, 2000);
    };
    // start
    setBadge(spinner, 'bg-amber-50 text-amber-700');
    setTimeout(poll, 1500);
});
</script>
</x-app-layout>
