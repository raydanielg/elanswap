<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Malipo ya Huduma
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6" id="payment-container" data-status-url="{{ route('payment.status') }}" data-dashboard-url="{{ route('dashboard') }}" data-initial-paid="{{ auth()->user()->hasPaid() ? 'true' : 'false' }}">

            @if (session('status'))
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="p-6 bg-white shadow sm:rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Hali ya Malipo</h3>
                    <div id="status-badge"></div>
                </div>

                <div id="payment-form-container">
                    <p class="text-sm text-gray-600 mb-4">Tafadhali ingiza namba yako ya simu ili kuanzisha malipo ya TZS {{ number_format($amount) }}. Utapokea ombi la malipo kwenye simu yako.</p>
                    <form id="payment-form" method="POST" action="{{ route('payment.pay') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Namba ya Simu</label>
                            <input id="phone" name="phone" type="tel" inputmode="numeric" pattern="[0-9+]{9,15}" maxlength="15" placeholder="07XXXXXXXX" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required>
                        </div>
                        <button id="payment-button" type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 transition">
                            <svg id="btn-spinner" class="hidden animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                            <span id="btn-text">Lipa Sasa</span>
                        </button>
                    </form>
                </div>

                <div id="paid-container" class="hidden text-center">
                    <div class="mx-auto mb-3 w-14 h-14 rounded-full bg-green-50 flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-green-700 font-semibold mb-4">Asante! Malipo yako yamekamilika.</p>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Endelea Kwenye Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

<script>
// MAELEZO: Script hii inasimamia mchakato wa malipo kwenye ukurasa
document.addEventListener('DOMContentLoaded', function () {
    // MAELEZO: Kupata vipengele muhimu vya ukurasa
    const container = document.getElementById('payment-container');
    const statusUrl = container.dataset.statusUrl; // URL ya kuangalia hali ya malipo
    let isPaid = container.dataset.initialPaid === 'true'; // Je, mtumiaji amelipa tayari?

    // MAELEZO: Kupata vipengele vya skrini ili kubadilisha mazingira
    const statusBadge = document.getElementById('status-badge'); // Alama ya hali ya malipo
    const paymentFormContainer = document.getElementById('payment-form-container'); // Fomu ya malipo
    const paidContainer = document.getElementById('paid-container'); // Eneo la ujumbe wa malipo yamekamilika
    const paymentButton = document.getElementById('payment-button'); // Kitufe cha "Lipa Sasa"
    const btnSpinner = document.getElementById('btn-spinner'); // Spinner ya kuonyesha kazi inaendelea
    const btnText = document.getElementById('btn-text'); // Maandishi ya kitufe

    // MAELEZO: Kazi ya kubadilisha mazingira kulingana na hali ya malipo
    const updateUI = (paid) => {
        if (paid) { // Ikiwa malipo yamekamilika
            statusBadge.innerHTML = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                PAID
            </span>`;
            paymentFormContainer.classList.add('hidden');
            paidContainer.classList.remove('hidden');
        } else { // Ikiwa malipo hayajakamilika
            // MAELEZO: Kuonyesha alama nyekundu ya "UNPAID"
            statusBadge.innerHTML = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                UNPAID
            </span>`;
            // MAELEZO: Kuonyesha fomu ya malipo na kuficha ujumbe wa mafanikio
            paymentFormContainer.classList.remove('hidden');
            paidContainer.classList.add('hidden');
        }
    };

    // MAELEZO: Kazi ya kuangalia hali ya malipo kila sekunde 3
    const pollStatus = async () => {
        if (isPaid) return; // Ikiwa tayari amelipa, acha kuangalia
        try {
            // MAELEZO: Kutuma ombi la kuangalia hali ya malipo
            const response = await fetch(statusUrl, { headers: { 'Accept': 'application/json' } });
            const data = await response.json();
            if (data.ok && data.paid) { // Ikiwa malipo yamekamilika
                isPaid = true;
                updateUI(true); // Badilisha mazingira kuonyesha mafanikio
            }
        } catch (error) {
            console.error('Hitilafu ya kuangalia hali ya malipo:', error);
        }
        if (!isPaid) {
            setTimeout(pollStatus, 3000); // Angalia tena baada ya sekunde 3
        }
    };

    // MAELEZO: Kuanzisha mazingira ya ukurasa mara ya kwanza
    updateUI(isPaid);
    
    // MAELEZO: Helper: hakikisha lottie imepakiwa kisha icheze animation
    const ensureLottieThenPlay = (containerId, jsonPath, options={}) => {
        const play = () => {
            try {
                window.lottie.loadAnimation({
                    container: document.getElementById(containerId),
                    renderer: options.renderer || 'svg',
                    loop: options.loop ?? false,
                    autoplay: options.autoplay ?? true,
                    path: jsonPath,
                    rendererSettings: options.rendererSettings || {}
                });
            } catch (_) {}
        };
        if (!window.lottie) {
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js';
            script.onload = play;
            document.body.appendChild(script);
        } else {
            play();
        }
    };

    // MAELEZO: Kuanza kuangalia hali ya malipo ikiwa bado hajalipa
    if (!isPaid) {
        setTimeout(pollStatus, 1000); // Anza kuangalia baada ya sekunde 1
    }

    // MAELEZO: Kusimamia utumaji wa fomu ya malipo
    const form = document.getElementById('payment-form');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault(); // Zuia utumaji wa kawaida wa fomu
            // MAELEZO: Kuzima kitufe na kuonyesha spinner wakati wa kutuma
            paymentButton.disabled = true;
            btnSpinner.classList.remove('hidden');
            btnText.textContent = 'Inatuma...';

            // MAELEZO: Kuficha ujumbe wa awali wa mfumo
            const sessionStatus = document.querySelector('.p-4.bg-green-50');
            if(sessionStatus) sessionStatus.style.display = 'none';
            const sessionError = document.querySelector('.p-4.bg-red-50');
            if(sessionError) sessionError.style.display = 'none';

            try {
                // MAELEZO: Kuandaa data ya fomu na kutuma ombi la malipo
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                const result = await response.json();

                if (result.ok) { // Ikiwa ombi limefanikiwa
                    const orderId = result.order_id || '';
                    // MAELEZO: Kuonyesha ujumbe wa Verifying... na spinner
                    statusBadge.innerHTML = `<div class="flex items-center text-sm text-blue-600">
                        <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                        <span>Verifying...</span>
                    </div>`;
                    // MAELEZO: Kubadilisha maandishi ya kitufe
                    btnText.textContent = 'Verifying...';

                    // MAELEZO: Kuanza kuangalia hali ya malipo kila sekunde 3 kwa dakika 2
                    const start = Date.now();
                    const timeoutMs = 3 * 60 * 1000; // Dakika 3
                    const intervalMs = 3000; // Sekunde 3
                    const poll = async () => {
                        try {
                            // MAELEZO: Kuangalia hali ya malipo kwa order_id maalum
                            const res = await fetch(`{{ url('/payment/status') }}${orderId ? ('?order_id=' + encodeURIComponent(orderId)) : ''}`, { headers: { 'Accept': 'application/json' } });
                            const st = await res.json();
                            if (st && st.paid) { // Ikiwa malipo yamekamilika
                                // MAELEZO: Kuonyesha ujumbe wa mafanikio na kutoa chaguo la kuendelea
                                statusBadge.innerHTML = `
                                    <span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800\">
                                        <span id=\"statusSuccessAnim\" class=\"w-4 h-4 mr-1\"></span>
                                        PAID
                                    </span>`;
                                btnSpinner.classList.add('hidden');
                                btnText.textContent = 'Imelipwa';
                                paymentFormContainer.classList.add('hidden');

                                const phone = (st.phone || '').toString();
                                const phoneLocal = phone.startsWith('255') ? ('0' + phone.slice(3)) : phone;
                                const amountFmt = (st.amount ? new Intl.NumberFormat('en-US').format(st.amount) : '');
                                const method = (st.method || '').toUpperCase();
                                const reference = st.reference || '';
                                paidContainer.innerHTML = `
                                    <div id="successAnim" class="mx-auto mb-3 w-24 h-24"></div>
                                    <h3 class="text-green-700 font-semibold mb-2">Hongera! Malipo yako yamekamilika.</h3>
                                    <div class="mb-4 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-md p-3 text-left">
                                        <div class="grid grid-cols-1 gap-1">
                                            <div><span class="text-gray-500">Method:</span> <span class="font-medium">${method || '—'}</span></div>
                                            <div><span class="text-gray-500">Phone:</span> <span class="font-medium">${phoneLocal || '—'}</span></div>
                                            <div><span class="text-gray-500">Amount:</span> <span class="font-medium">${st.currency || ''} ${amountFmt || ''}</span></div>
                                            <div><span class="text-gray-500">Reference:</span> <span class="font-medium">${reference || '—'}</span></div>
                                        </div>
                                    </div>
                                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Endelea Kwenye Dashboard
                                    </a>
                                `;
                                paidContainer.classList.remove('hidden');
                                // Cheza Lottie success animation kutoka /success.json (badge na kadi)
                                ensureLottieThenPlay('statusSuccessAnim', '/success.json', { loop: false, autoplay: true });
                                ensureLottieThenPlay('successAnim', '/success.json', { loop: false, autoplay: true });
                                return; // Acha kuangalia
                            }
                            if (Date.now() - start < timeoutMs) { // Ikiwa bado kuna muda
                                setTimeout(poll, intervalMs); // Angalia tena
                            } else {
                                // MAELEZO: Muda umeisha - wezesha kitufe tena
                                statusBadge.innerHTML = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Bado haijathibitishwa. Jaribu tena au angalia baadaye.</span>`;
                                paymentButton.disabled = false;
                                btnSpinner.classList.add('hidden');
                                btnText.textContent = 'Lipa Sasa';
                            }
                        } catch (e) {
                            // MAELEZO: Ikiwa kuna hitilafu, jaribu tena
                            setTimeout(poll, intervalMs);
                        }
                    };
                    setTimeout(poll, 2500); // Mpe mtumiaji muda wa kuthibitisha kwanza
                } else { // Ikiwa ombi halija fanikiwa
                    // MAELEZO: Kuonyesha ujumbe wa hitilafu
                    statusBadge.innerHTML = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        ${result.message || 'Imeshindikana kutuma ombi.'}
                    </span>`;
                    // MAELEZO: Kuwezesha kitufe tena
                    paymentButton.disabled = false;
                    btnSpinner.classList.add('hidden');
                    btnText.textContent = 'Lipa Sasa';
                }
            } catch (error) { // Ikiwa kuna hitilafu ya mtandao
                // MAELEZO: Kuonyesha ujumbe wa hitilafu ya mtandao
                statusBadge.innerHTML = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    Kuna tatizo la mtandao. Jaribu tena.
                </span>`;
                // MAELEZO: Kuwezesha kitufe tena
                paymentButton.disabled = false;
                btnSpinner.classList.add('hidden');
                btnText.textContent = 'Lipa Sasa';
            }
        });
    }
});
</script>
</x-app-layout>
