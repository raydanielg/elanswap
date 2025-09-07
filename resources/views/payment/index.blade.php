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
                <h3 class="text-lg font-semibold mb-2">Muhtasari wa Malipo</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="p-3 rounded border bg-gray-50">
                        <div class="text-gray-500">Kiasi</div>
                        <div class="font-semibold">TZS {{ number_format($amount) }}</div>
                    </div>
                    <div class="p-3 rounded border bg-gray-50">
                        <div class="text-gray-500">Hali</div>
                        <div>
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
                            @if($latest && $latest->paid_at)
                                <div class="mt-1 text-gray-700">Imethibitishwa: <span id="paidTime">{{ $latest->paid_at->format('Y-m-d H:i') }}</span></div>
                            @endif
                            <div id="statusAlert" class="mt-2 hidden"></div>
                        </div>
                    </div>
                </div>
            </div>

            @if(!auth()->user()->hasPaid())
                <div class="p-6 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-semibold mb-1">Lipa Sasa</h3>
                    <p class="text-sm text-gray-600 mb-3">Chagua njia ya malipo na ingiza namba ya simu. Mfumo utatuma ombi la malipo moja kwa moja kwenye simu yako.</p>
                    
                    @if(session('error'))
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('payment.pay') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="method" class="block text-sm font-medium text-gray-700">Njia ya Malipo</label>
                            <select id="method" name="method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required>
                                <option value="">Chagua njia ya malipo</option>
                                <option value="mpesa">M-Pesa</option>
                                <option value="tigopesa">Tigo Pesa</option>
                                <option value="airtel">Airtel Money</option>
                            </select>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Namba ya Simu</label>
                            <input id="phone" name="phone" type="tel" inputmode="numeric" pattern="[0-9+]{9,15}" maxlength="15" placeholder="07XXXXXXXX au 2557XXXXXXXX" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required>
                            <p class="mt-1 text-xs text-gray-500">Ingiza namba utakayopokea ombi la malipo. Mfano: 0712XXXXXX au 2557XXXXXXX.</p>
                        </div>
                        @error('phone')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('method')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="flex items-center justify-end gap-3">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition">
                                Tuma Ombi la Malipo
                            </button>
                        </div>
                    </form>
                    <p class="mt-2 text-xs text-gray-500">Baada ya kubonyeza, utapokea ombi la malipo moja kwa moja kwenye simu yako. Thibitisha ombi hilo ili kukamilisha malipo.</p>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Phone input: allow digits and plus, strip others
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', () => {
            const cleaned = phoneInput.value.replace(/[^0-9+]/g, '');
            if (cleaned !== phoneInput.value) phoneInput.value = cleaned;
        });
    }

        // Summary card live status
        document.addEventListener('DOMContentLoaded', function () {
            const statusUrl = '{{ route('payment.status') }}';
            const summary = document.getElementById('summaryCard');
            if (!summary) return;
            const hasLatest = summary.getAttribute('data-has-latest') === '1';
            const isPaid = summary.getAttribute('data-paid') === '1';
            const badge = document.getElementById('statusBadge');
            const timeInfo = document.getElementById('timeInfo');
            const paidTime = document.getElementById('paidTime');
            const alertBox = document.getElementById('statusAlert');

            if (!badge) return;

            const setBadge = (html, classes) => { if (badge) { badge.innerHTML = html; badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium ' + classes; } };
            const setAlert = (text, classes) => { if (!alertBox) return; alertBox.className = 'mt-2 rounded-md p-3 text-sm ' + classes; alertBox.textContent = text; alertBox.classList.remove('hidden'); };

            const spinner = '<svg class="animate-spin h-3 w-3 text-amber-500 mr-1" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg> PENDING';
            const paidDot = '<svg class="animate-pulse h-3 w-3 text-green-500 mr-1" viewBox="0 0 8 8" fill="currentColor"><circle cx="4" cy="4" r="4"/></svg> PAID';
            const failedIcon = '<svg class="h-3 w-3 text-red-500 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5h2v2H9v-2zm0-8h2v6H9V5z" clip-rule="evenodd"/></svg> FAILED';

            const start = Date.now();
            const timeoutMs = 120000;

            const poll = async () => {
                try {
                    const r = await fetch(statusUrl, { headers: { 'Accept': 'application/json' } });
                    const s = await r.json();
                    if (s && s.ok) {
                        if (s.paid) {
                            setBadge(paidDot, 'bg-green-100 text-green-800');
                            if (timeInfo) timeInfo.textContent = '';
                            if (paidTime && s.paid_at) paidTime.textContent = new Date(s.paid_at).toLocaleString();
                            if (alertBox) alertBox.classList.add('hidden');
                            return; // stop polling
                        } else {
                            setBadge(spinner, 'bg-amber-50 text-amber-700');
                            if (Date.now() - start > timeoutMs) {
                                setBadge(failedIcon, 'bg-red-100 text-red-800');
                                setAlert('Malipo hayajakamilika kwa muda uliowekwa. Tafadhali jaribu tena au hakikisha umethibitisha kwenye simu.', 'bg-red-50 border border-red-200 text-red-800');
                                return; // stop polling
                            }
                        }
                    }
                } catch (_) {}
                setTimeout(poll, 2000);
            };
            setBadge(spinner, 'bg-amber-50 text-amber-700');
            setTimeout(poll, 1500);
        });
    </script>
</x-app-layout>
