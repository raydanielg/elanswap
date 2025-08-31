<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Malipo ya Huduma
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="p-6 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Muhtasari</h3>
                <p class="text-gray-700">Kiasi cha kulipa: <span class="font-medium">TZS {{ number_format($amount) }}</span></p>

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
                        <span id="methodBadge" class="text-gray-600">Njia: {{ strtoupper($latest->method ?? '-') }}</span>
                        <span id="timeInfo" class="text-gray-500"></span>
                    </div>
                    @if($latest && $latest->paid_at)
                        <div class="mt-1 text-gray-700">Imethibitishwa: <span id="paidTime">{{ $latest->paid_at->format('Y-m-d H:i') }}</span></div>
                    @endif
                    <div id="statusAlert" class="mt-3 hidden"></div>
                </div>
            </div>

            @if(!auth()->user()->hasPaid())
                <div class="p-6 bg-white shadow sm:rounded-lg" x-data>
                    <h3 class="text-lg font-semibold mb-4">Weka Namba ya Simu</h3>
                    <p class="text-sm text-gray-600 mb-3">Ingiza namba ya simu utakayopokea ombi la malipo (mf. 07XXXXXXXX au 2557XXXXXXXX).</p>
                    <form id="pushForm" method="POST" action="{{ route('payment.push') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Namba ya Simu</label>
                            <input id="phone" name="phone" type="text" inputmode="tel" placeholder="07XXXXXXXX au 2557XXXXXXXX" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required>
                        </div>

                        @error('phone')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <button id="pushBtn" type="submit" class="mt-2 inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-25 transition">Tuma Ombi la Malipo</button>
                    </form>
                    <div id="pushStatus" class="mt-3 text-sm text-gray-700 hidden"></div>
                    <p class="mt-2 text-xs text-gray-500">Baada ya kutuma, thibitisha ombi la malipo kwenye simu yako.</p>
                </div>
            @else
                <div class="p-6 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-800">Asante! Malipo yako yamekamilika. Unaweza kuendelea kutumia huduma zote.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('pushForm');
    if (!form) return;
    const btn = document.getElementById('pushBtn');
    const statusBox = document.getElementById('pushStatus');
    const pushUrl = form.getAttribute('action');
    const statusUrl = '{{ route('payment.status') }}';
    const dashboardUrl = '{{ route('dashboard') }}';

    const show = (el) => { el.classList.remove('hidden'); };
    const hide = (el) => { el.classList.add('hidden'); };

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (btn) { btn.disabled = true; btn.textContent = 'Inatuma...'; }
        if (statusBox) { statusBox.textContent = 'Inatuma ombi la malipo...'; show(statusBox); }

        try {
            const formData = new FormData(form);
            const res = await fetch(pushUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: formData
            });
            const data = await res.json();
            if (!data.ok) throw new Error((data && data.message) || 'Imeshindikana kutuma ombi');

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
                    const r = await fetch(statusUrl, { headers: { 'Accept': 'application/json' } });
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
            if (btn) { btn.disabled = false; btn.textContent = 'Tuma Ombi la Malipo'; }
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
                    if (timeInfo && s.paid_at) timeInfo.textContent = new Date(s.paid_at).toLocaleString();
                    if (paidTime && s.paid_at) paidTime.textContent = new Date(s.paid_at).toLocaleString();
                    if (alertBox) alertBox.classList.add('hidden');
                    return; // stop polling
                } else {
                    const elapsed = Math.round((Date.now() - start) / 1000);
                    setBadge(spinner, 'bg-amber-50 text-amber-700');
                    if (timeInfo) timeInfo.textContent = 'Imesubiri sekunde ' + elapsed;
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
