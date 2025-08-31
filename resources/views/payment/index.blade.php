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

                @if($latest)
                    <div class="mt-4 text-sm">
                        <p class="text-gray-600">Hali ya malipo yako ya karibuni:</p>
                        <ul class="list-disc ml-5 text-gray-700">
                            <li>Hali: <span class="font-medium">{{ strtoupper($latest->status) }}</span></li>
                            <li>Njia: {{ strtoupper($latest->method ?? '-') }}</li>
                            @if($latest->paid_at)
                                <li>Imethibitishwa: {{ $latest->paid_at->format('Y-m-d H:i') }}</li>
                            @endif
                        </ul>
                    </div>
                @endif
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
</script>
