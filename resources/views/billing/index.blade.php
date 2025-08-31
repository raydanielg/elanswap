<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-center text-gray-900">Complete Your Payment</h2>
        <p class="text-center text-gray-600 mt-2">Pay to unlock all features. Amount: <strong>{{ number_format($amount) }} TZS</strong></p>
        @if(session('status'))
            <div class="mt-4 p-3 text-sm bg-blue-50 text-blue-700 rounded">{{ session('status') }}</div>
        @endif
        @if($latest && $latest->status === 'pending')
            <div class="mt-4 p-3 text-sm bg-yellow-50 text-yellow-800 rounded">Your last payment is pending. Please complete it.</div>
        @endif
    </div>

    <form method="POST" action="{{ route('billing.create') }}" class="space-y-6" id="billing-form">
        @csrf
        <input type="hidden" name="method" value="mpesa" />

        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone (for mobile money)</label>
            <input id="phone" name="phone" type="tel" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" placeholder="07XXXXXXXX">
            @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" id="pay-btn">
                Lipia Sasa
            </button>
        </div>
    </form>

    <div id="waiting" class="hidden mt-4 p-3 text-sm bg-yellow-50 text-yellow-800 rounded">
        Tafadhali subiri... tumetuma ombi la malipo. Thibitisha kwenye simu yako.
    </div>

    @env('local')
        @if($latest)
            <div class="mt-8 border-t pt-4">
                <p class="text-sm text-gray-600 mb-2">Developer shortcuts (local only):</p>
                <div class="flex gap-2">
                    <a href="{{ route('billing.demo.success', $latest) }}" class="px-3 py-1.5 bg-green-600 text-white rounded">Mark Success</a>
                    <a href="{{ route('billing.demo.fail', $latest) }}" class="px-3 py-1.5 bg-red-600 text-white rounded">Mark Failed</a>
                </div>
            </div>
        @endif
    @endenv
    
    <script>
        (function () {
            const statusUrl = @json(route('billing.status'));
            const redirectUrl = @json(route('dashboard'));
            const waiting = document.getElementById('waiting');
            const form = document.getElementById('billing-form');
            const btn = document.getElementById('pay-btn');
            const phoneInput = document.getElementById('phone');
            const tokenInput = form?.querySelector('input[name="_token"]');

            function poll() {
                fetch(statusUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
                    .then(r => r.json())
                    .then(data => {
                        if (data && data.paid) {
                            window.location.href = redirectUrl;
                        } else {
                            setTimeout(poll, 3000);
                        }
                    })
                    .catch(() => setTimeout(poll, 4000));
            }

            // Start polling immediately if there is a pending payment or a status flash
            @if(($latest && $latest->status === 'pending') || session('status'))
                waiting.classList.remove('hidden');
                poll();
            @endif

            // Intercept submit to make AJAX call without reload
            form?.addEventListener('submit', function (e) {
                e.preventDefault();
                const fd = new FormData(form);
                // Ensure Accept JSON to trigger JSON path in controller
                waiting.classList.remove('hidden');
                btn.disabled = true;
                phoneInput?.setAttribute('readonly', 'readonly');

                fetch(@json(route('billing.create')), {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': tokenInput?.value || '',
                    },
                    body: fd,
                })
                .then(async (r) => {
                    if (!r.ok) {
                        const err = await r.json().catch(() => ({}));
                        throw new Error(err.message || 'Payment initiation failed');
                    }
                    return r.json();
                })
                .then((resp) => {
                    // If Selcom provided a hosted payment URL, send the user there immediately
                    if (resp && resp.payment_url) {
                        window.location.href = resp.payment_url;
                        return;
                    }
                    // Otherwise, start polling for push/completion
                    setTimeout(poll, 1200);
                })
                .catch((e) => {
                    // Show a small error and re-enable UI
                    alert(e.message || 'Imeshindikana kuanzisha malipo. Jaribu tena.');
                    waiting.classList.add('hidden');
                    btn.disabled = false;
                    phoneInput?.removeAttribute('readonly');
                });
            });
        })();
    </script>
</x-guest-layout>
