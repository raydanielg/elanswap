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
                    <p class="text-green-700 font-semibold mb-4">Asante! Malipo yako yamekamilika.</p>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Endelea Kwenye Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('payment-container');
    const statusUrl = container.dataset.statusUrl;
    let isPaid = container.dataset.initialPaid === 'true';

    const statusBadge = document.getElementById('status-badge');
    const paymentFormContainer = document.getElementById('payment-form-container');
    const paidContainer = document.getElementById('paid-container');
    const paymentButton = document.getElementById('payment-button');
    const btnSpinner = document.getElementById('btn-spinner');
    const btnText = document.getElementById('btn-text');

    const updateUI = (paid) => {
        if (paid) {
            statusBadge.innerHTML = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                PAID
            </span>`;
            paymentFormContainer.classList.add('hidden');
            paidContainer.classList.remove('hidden');
        } else {
            statusBadge.innerHTML = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                UNPAID
            </span>`;
            paymentFormContainer.classList.remove('hidden');
            paidContainer.classList.add('hidden');
        }
    };

    const pollStatus = async () => {
        if (isPaid) return;
        try {
            const response = await fetch(statusUrl, { headers: { 'Accept': 'application/json' } });
            const data = await response.json();
            if (data.ok && data.paid) {
                isPaid = true;
                updateUI(true);
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
        if (!isPaid) {
            setTimeout(pollStatus, 3000); // Poll every 3 seconds
        }
    };

    // Initial UI setup
    updateUI(isPaid);
    
    // Start polling if not already paid
    if (!isPaid) {
        setTimeout(pollStatus, 1000);
    }

    // Form submission
    const form = document.getElementById('payment-form');
    if (form) {
        form.addEventListener('submit', function() {
            paymentButton.disabled = true;
            btnSpinner.classList.remove('hidden');
            btnText.textContent = 'Inatuma...';
        });
    }
});
</script>
</x-app-layout>
